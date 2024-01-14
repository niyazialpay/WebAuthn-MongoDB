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
     * @param WebAuthnAuthenticatable $user
     * @param Request $request
     * @param Challenge|null  $challenge
     * @param AttestationObject|null  $attestationObject
     * @param ClientDataJson|null  $clientDataJson
     * @param WebAuthnCredential|null  $credential
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
