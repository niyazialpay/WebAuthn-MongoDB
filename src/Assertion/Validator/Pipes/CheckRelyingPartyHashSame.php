<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\SharedPipes\CheckRelyingPartyHashSame as BaseCheckRelyingPartyHashSame;

/**
 * @internal
 */
class CheckRelyingPartyHashSame extends BaseCheckRelyingPartyHashSame
{
    /**
     * Return the Attestation data to check the RP ID Hash.
     *
     * @param AttestationValidation|AssertionValidation $validation
     * @return AuthenticatorData
     */
    protected function authenticatorData(AssertionValidation|AttestationValidation $validation): AuthenticatorData
    {
        return $validation->authenticatorData;
    }

    /**
     * Return the Relying Party ID from the config or credential.
     *
     * @param AssertionValidation|AttestationValidation $validation
     * @return string
     */
    protected function relyingPartyId(AssertionValidation|AttestationValidation $validation): string
    {
        return $validation->credential->rp_id;
    }
}
