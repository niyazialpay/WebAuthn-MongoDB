<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;

/**
 * @internal
 */
class CheckCredentialIsWebAuthnGet
{
    /**
     * Handle the incoming Assertion Validation.
     *
     * @param AssertionValidation $validation
     * @param Closure $next
     * @return mixed
     * @throws AssertionException
     */
    public function handle(AssertionValidation $validation, Closure $next): mixed
    {
        if ($validation->clientDataJson->type !== 'webauthn.get') {
            throw AssertionException::make('Client Data type is not [webauthn.get].');
        }

        return $next($validation);
    }
}
