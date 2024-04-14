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
     */
    protected static function throw(AttestationValidation|AssertionValidation $validation, string $message): never
    {
        throw $validation instanceof AssertionValidation
            ? AssertionException::make($message)
            : AttestationException::make($message);
    }
}
