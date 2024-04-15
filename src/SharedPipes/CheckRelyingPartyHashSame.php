<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;

/**
 * @internal
 */
abstract class CheckRelyingPartyHashSame
{
    use ThrowsCeremonyException;

    /**
     * Create a new pipe instance.
     */
    public function __construct(protected Repository $config)
    {
        //
    }

    /**
     * Handle the incoming WebAuthn Ceremony Validation.
     *
     * @throws AssertionException
     * @throws AttestationException
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        // This way we can get the app RP ID on attestation, and the Credential RP ID
        // on assertion. The credential will have the same Relying Party ID on both
        // the authenticator and the application so on assertion both should match.
        if ($this->authenticatorData($validation)->hasNotSameRPIdHash($this->relyingPartyId($validation))) {
            static::throw($validation, 'Response has different Relying Party ID hash.');
        }

        return $next($validation);
    }

    /**
     * Return the Attestation data to check the RP ID Hash.
     */
    abstract protected function authenticatorData(
        AttestationValidation|AssertionValidation $validation
    ): AuthenticatorData;

    /**
     * Return the Relying Party ID from the config or credential.
     */
    abstract protected function relyingPartyId(AssertionValidation|AttestationValidation $validation): string;
}
