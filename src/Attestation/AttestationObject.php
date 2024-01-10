<?php

namespace niyazialpay\WebAuthn\Attestation;

use niyazialpay\WebAuthn\Attestation\Formats\Format;

/**
 * @internal
 */
class AttestationObject
{
    /**
     * Create a new Attestation Object.
     *
     * @param  \niyazialpay\WebAuthn\Attestation\AuthenticatorData  $authenticatorData
     * @param  \niyazialpay\WebAuthn\Attestation\Formats\Format  $format
     * @param  string  $formatName
     */
    public function __construct(
        public AuthenticatorData $authenticatorData,
        public Format $format,
        public string $formatName)
    {
        //
    }
}
