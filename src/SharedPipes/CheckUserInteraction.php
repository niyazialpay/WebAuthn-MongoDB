<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;

/**
 * @internal
 */
abstract class CheckUserInteraction
{
    use ThrowsCeremonyException;

    /**
     * Handle the incoming WebAuthn Ceremony Validation.
     *
     * @throws AssertionException
     * @throws AttestationException
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        $notPresent = $validation instanceof AttestationValidation
            ? $validation->attestationObject->authenticatorData->wasUserAbsent()
            : $validation->authenticatorData->wasUserAbsent();

        if ($notPresent) {
            static::throw($validation, 'Response did not have the user present.');
        }

        // Only verify the user if the challenge required it.
        if ($validation->challenge->verify) {
            $notVerified = $validation instanceof AttestationValidation
                ? $validation->attestationObject->authenticatorData->wasUserNotVerified()
                : $validation->authenticatorData->wasUserNotVerified();

            if ($notVerified) {
                static::throw($validation, 'Response did not verify the user.');
            }
        }

        return $next($validation);
    }
}
