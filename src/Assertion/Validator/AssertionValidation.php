<?php

namespace niyazialpay\WebAuthn\Assertion\Validator;

use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Challenge;
use niyazialpay\WebAuthn\ClientDataJson;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class AssertionValidation
{
    /**
     * Create a new Assertion Validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable|null  $user
     * @param  \niyazialpay\WebAuthn\Challenge|null  $challenge
     * @param  \niyazialpay\WebAuthn\Models\WebAuthnCredential|null  $credential
     * @param  \niyazialpay\WebAuthn\ClientDataJson|null  $clientDataJson
     * @param  \niyazialpay\WebAuthn\Attestation\AuthenticatorData|null  $authenticatorData
     */
    public function __construct(
        public Request $request,
        public ?WebAuthnAuthenticatable $user = null,
        public ?Challenge $challenge = null,
        public ?WebAuthnCredential $credential = null,
        public ?ClientDataJson $clientDataJson = null,
        public ?AuthenticatorData $authenticatorData = null,
    )
    {
        //
    }
}
