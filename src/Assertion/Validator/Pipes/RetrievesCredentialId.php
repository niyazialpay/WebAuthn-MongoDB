<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;
use function in_array;

/**
 * @internal
 */
class RetrievesCredentialId
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
        $id = $validation->request->json('id');

        // First, always check the challenge credentials before finding the real one.
        if ($this->credentialNotInChallenge($id, $validation->challenge->properties)) {
            throw AssertionException::make('Credential is not on accepted list.');
        }

        // We can now find the credential.
        $validation->credential = WebAuthnCredential::whereKey($id)->first();

        if (!$validation->credential) {
            throw AssertionException::make('Credential ID does not exist.');
        }

        if ($validation->credential->isDisabled()) {
            throw AssertionException::make('Credential ID is blacklisted.');
        }

        return $next($validation);
    }

    /**
     * Check if the previous Assertion request specified a credentials list to accept.
     *
     * @param  string  $id
     * @param  array  $properties
     * @return bool
     */
    protected function credentialNotInChallenge(string $id, array $properties): bool
    {
        return isset($properties['credentials']) && ! in_array($id, $properties['credentials'], true);
    }
}
