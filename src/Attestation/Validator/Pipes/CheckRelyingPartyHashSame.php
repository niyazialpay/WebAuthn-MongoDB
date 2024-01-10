<?php

namespace niyazialpay\WebAuthn\Attestation\Validator\Pipes;

use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\SharedPipes\CheckRelyingPartyHashSame as BaseCheckRelyingPartyHashSame;

/**
 * 13. Verify that the rpIdHash in authData is the SHA-256 hash of the RP ID expected by the Relying Party.
 *
 * @see https://www.w3.org/TR/webauthn-2/#sctn-registering-a-new-credential
 *
 * @internal
 */
class CheckRelyingPartyHashSame extends BaseCheckRelyingPartyHashSame
{
    /**
     * Return the Attestation data to check the RP ID Hash.
     *
     * @param  \niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation|\niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation  $validation
     * @return \niyazialpay\WebAuthn\Attestation\AuthenticatorData
     */
    protected function authenticatorData(AssertionValidation|AttestationValidation $validation): AuthenticatorData
    {
        return $validation->attestationObject->authenticatorData;
    }

    /**
     * Return the Relying Party ID from the config or credential.
     *
     * @param  \niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation|\niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation  $validation
     * @return string
     */
    protected function relyingPartyId(AssertionValidation|AttestationValidation $validation): string
    {
        return $this->config->get('webauthn.relying_party.id') ?? $this->config->get('app.url');
    }
}
