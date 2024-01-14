<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;
use function parse_url;
use const PHP_URL_HOST;

/**
 * @internal
 */
abstract class CheckRelyingPartyHashSame
{
    use ThrowsCeremonyException;

    /**
     * Create a new pipe instance.
     *
     * @param Repository $config
     */
    public function __construct(protected Repository $config)
    {
        //
    }

    /**
     * Handle the incoming WebAuthn Ceremony Validation.
     *
     * @param AttestationValidation|AssertionValidation $validation
     * @param Closure $next
     * @return mixed
     * @throws AssertionException
     * @throws AttestationException
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        // This way we can get the app RP ID on attestation, and the Credential RP ID
        // on assertion. The credential will have the same Relying Party ID on both
        // the authenticator and the application so on assertion both should match.
        $relyingParty = parse_url($this->relyingPartyId($validation), PHP_URL_HOST);

        if ($this->authenticatorData($validation)->hasNotSameRPIdHash($relyingParty)) {
            static::throw($validation, 'Response has different Relying Party ID hash.');
        }

        return $next($validation);
    }

    /**
     * Return the Attestation data to check the RP ID Hash.
     *
     * @param AttestationValidation|AssertionValidation $validation
     * @return AuthenticatorData
     */
    abstract protected function authenticatorData(
        AttestationValidation|AssertionValidation $validation
    ): AuthenticatorData;

    /**
     * Return the Relying Party ID from the config or credential.
     *
     * @param AssertionValidation|AttestationValidation $validation
     * @return string
     */
    abstract protected function relyingPartyId(AssertionValidation|AttestationValidation $validation): string;
}
