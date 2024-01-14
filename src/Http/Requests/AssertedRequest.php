<?php

namespace niyazialpay\WebAuthn\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

class AssertedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'id' => "string", 'rawId' => "string", 'response.authenticatorData' => "string",
        'response.clientDataJSON' => "string", 'response.signature' => "string", 'response.userHandle' => "string",
        'type' => "string"
    ])]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'rawId' => ['required', 'string'],
            'response.authenticatorData' => ['required', 'string'],
            'response.clientDataJSON' => ['required', 'string'],
            'response.signature' => ['required', 'string'],
            'response.userHandle' => ['sometimes', 'nullable'],
            'type' => ['required', 'string'],
        ];
    }

    /**
     * Check if the login request wants to remember the user as stateful.
     *
     * @return bool
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
     */
    public function login(string $guard = null, bool $remember = null, bool $destroySession = false): ?Authenticatable
    {
        /** @var StatefulGuard $auth */
        $auth = Auth::guard($guard);

        if ($auth->attempt($this->validated(), $remember ?? $this->hasRemember())) {
            $this->session()->regenerate($destroySession);

            return $auth->user();
        }

        return null;
    }
}
