<?php

namespace niyazialpay\WebAuthn\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;

use function auth;

class AssertedRequest extends FormRequest
{
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
            'response.authenticatorData' => 'required|string',
            'response.clientDataJSON' => 'required|string',
            'response.signature' => 'required|string',
            'response.userHandle' => 'sometimes|nullable',
            'type' => 'required|string',
        ];
    }

    /**
     * Check if the login request wants to remember the user as stateful.
     */
    public function hasRemember(): bool
    {
        return $this->hasHeader('X-WebAuthn-Remember')
            || $this->hasHeader('WebAuthn-Remember')
            || $this->filled('remember');
    }

    /**
     * Logs in the user for this assertion request.
     *
     * @param string|null $guard
     * @param bool|null $remember
     * @param bool $destroySession
     * @return Authenticatable|null
     * @phpstan-ignore-next-line
     *
     */
    public function login(
        string $guard = null,
        bool $remember = null,
        bool $destroySession = false
    ): ?Authenticatable
    {
        /** @var StatefulGuard $auth */
        $auth = auth()->guard($guard);

        if ($auth->attempt($this->validated(), $remember ?? $this->hasRemember())) {
            $this->session()->regenerate($destroySession);

            // @phpstan-ignore-next-line
            return $auth->user();
        }

        return null;
    }
}
