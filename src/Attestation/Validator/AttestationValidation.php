<?php

namespace niyazialpay\WebAuthn\Attestation\Validator;

use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Attestation\AttestationObject;
use niyazialpay\WebAuthn\Challenge;
use niyazialpay\WebAuthn\ClientDataJson;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class AttestationValidation
{
    /**
     * Create a new Attestation Validation procedure
     *
     * @param  \niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable  $user
     * @param  \Illuminate\Http\Request  $request
     * @param  \niyazialpay\WebAuthn\Challenge|null  $challenge
     * @param  \niyazialpay\WebAuthn\Attestation\AttestationObject|null  $attestationObject
     * @param  \niyazialpay\WebAuthn\ClientDataJson|null  $clientDataJson
     * @param  \niyazialpay\WebAuthn\Models\WebAuthnCredential|null  $credential
     */
    public function __construct(
        public WebAuthnAuthenticatable $user,
        public Request $request,
        public ?Challenge $challenge = null,
        public ?AttestationObject $attestationObject = null,
        public ?ClientDataJson $clientDataJson = null,
        public ?WebAuthnCredential $credential = null,
    )
    {
        //
    }
}
