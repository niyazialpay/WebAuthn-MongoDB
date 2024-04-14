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
     */
    public function __construct(
        public AuthenticatorData $authenticatorData,
        public Format $format,
        public string $formatName
    ) {
        //
    }
}
