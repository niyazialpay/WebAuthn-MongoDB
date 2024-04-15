<?php

namespace niyazialpay\WebAuthn\Contracts;


use MongoDB\Laravel\Relations\MorphMany;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;
use Ramsey\Uuid\UuidInterface;

interface WebAuthnAuthenticatable
{
    /**
     * Returns displayable data to be used to create WebAuthn Credentials.
     *
     * @return array{name: string, displayName: string}
     */
    public function webAuthnData(): array;

    /**
     * An anonymized user identity string, as a UUID.
     *
     * @see https://www.w3.org/TR/webauthn-2/#dom-publickeycredentialuserentity-id
     */
    public function webAuthnId(): UuidInterface;

    /**
     * Removes all credentials previously registered.
     */
    public function flushCredentials(string ...$except): void;

    /**
     * Disables all credentials for the user.
     */
    public function disableAllCredentials(string ...$except): void;

    /**
     * Makes an instance of a WebAuthn Credential attached to this user.
     */
    public function makeWebAuthnCredential(array $properties): WebAuthnCredential;

    /**
     * Returns a queryable relationship for its WebAuthn Credentials.
     *
     * @phpstan-ignore-next-line
     *
     * @return MorphMany
     */
    public function webAuthnCredentials(): MorphMany;
}
