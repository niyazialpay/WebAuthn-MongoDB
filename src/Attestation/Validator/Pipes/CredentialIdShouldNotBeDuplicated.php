<?php

namespace niyazialpay\WebAuthn\Attestation\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AttestationException;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

/**
 * @internal
 */
class CredentialIdShouldNotBeDuplicated
{
    /**
     * Handle the incoming Attestation Validation.
     *
     * @throws \niyazialpay\WebAuthn\Exceptions\AttestationException
     */
    public function handle(AttestationValidation $validation, Closure $next): mixed
    {
        if ($this->credentialAlreadyExists($validation)) {
            throw AttestationException::make('Credential ID already exists in the database.');
        }

        return $next($validation);
    }

    /**
     * Finds a WebAuthn Credential by the issued ID.
     */
    protected function credentialAlreadyExists(AttestationValidation $validation): bool
    {
        return WebAuthnCredential::whereKey($validation->request->json('id'))->exists();
    }
}
