<?php

namespace niyazialpay\WebAuthn\Http\Requests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidator;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Events\CredentialCreated;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

use function is_callable;

/**
 * @method WebAuthnAuthenticatable user($guard = null)
 */
class AttestedRequest extends FormRequest
{
    /**
     * The new credential instance.
     */
    protected WebAuthnCredential $credential;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(?WebAuthnAuthenticatable $user): bool
    {
        return (bool) $user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'rawId' => 'required|string',
            'response' => 'required|array',
            'response.clientDataJSON' => 'required|string',
            'response.attestationObject' => 'required|string',
            'type' => 'required|string',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @throws BindingResolutionException
     */
    protected function passedValidation(): void
    {
        $this->credential = $this->container->make(AttestationValidator::class)
            ->send(new AttestationValidation($this->user(), $this))
            ->then(static function (AttestationValidation $validation): WebAuthnCredential {
                return $validation->credential;
            });
    }

    /**
     * Save the generated WebAuthn Credentials, and return its ID.
     *
     * @param  array<string, mixed>|callable  $saving
     */
    public function save(array|callable $saving = []): string
    {
        is_callable($saving) ? $saving($this->credential) : $this->credential->forceFill($saving);

        $this->credential->save();

        CredentialCreated::dispatch($this->user(), $this->credential);

        return $this->credential->_id;
    }
}
