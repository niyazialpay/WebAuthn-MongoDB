<?php

namespace niyazialpay\WebAuthn\Attestation\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AttestationException;

/**
 * 7. Verify that the value of C.type is webauthn.create.
 *
 * @see https://www.w3.org/TR/webauthn-2/#sctn-registering-a-new-credential
 *
 * @internal
 */
class AttestationIsForCreation
{
    /**
     * Handle the incoming Attestation Validation.
     *
     * @throws \niyazialpay\WebAuthn\Exceptions\AttestationException
     */
    public function handle(AttestationValidation $validation, Closure $next): mixed
    {
        if ($validation->clientDataJson->type !== 'webauthn.create') {
            throw AttestationException::make('Response is not for creating WebAuthn Credentials.');
        }

        return $next($validation);
    }
}
