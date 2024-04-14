<?php

namespace niyazialpay\WebAuthn\Attestation\Creator;

use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Enums\ResidentKey;
use niyazialpay\WebAuthn\Enums\UserVerification;
use niyazialpay\WebAuthn\JsonTransport;

class AttestationCreation
{
    /**
     * Create a new Attestation Instructions instance.
     */
    public function __construct(
        public WebAuthnAuthenticatable $user,
        public Request $request,
        public ?ResidentKey $residentKey = null,
        public ?UserVerification $userVerification = null,
        public bool $uniqueCredentials = true,
        public JsonTransport $json = new JsonTransport()
    ) {
        //
    }
}
