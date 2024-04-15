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
abstract class CheckChallengeSame
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
        if ($validation->clientDataJson->challenge->hasNoLength()) {
            static::throw($validation, 'Response has an empty challenge.');
        }

        if ($validation->clientDataJson->challenge->hashNotEqual($validation->challenge->data)) {
            static::throw($validation, 'Response challenge is not equal.');
        }

        return $next($validation);
    }
}
