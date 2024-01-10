<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;

/**
 * @internal
 */
trait ThrowsCeremonyException
{
    /**
     * Throws an exception for the validation.
     *
     * @param  \niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation|\niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation  $validation
     * @param  string  $message
     * @return void&never
     * @throws \niyazialpay\WebAuthn\Exceptions\AssertionException|\niyazialpay\WebAuthn\Exceptions\AttestationException
     */
    protected static function throw(AttestationValidation|AssertionValidation $validation, string $message): void
    {
        throw $validation instanceof AssertionValidation
            ? AssertionException::make($message)
            : AttestationException::make($message);
    }
}
