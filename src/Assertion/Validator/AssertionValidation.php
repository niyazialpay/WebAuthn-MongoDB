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
     * @param Request $request
     * @param WebAuthnAuthenticatable|null  $user
     * @param Challenge|null  $challenge
     * @param WebAuthnCredential|null  $credential
     * @param ClientDataJson|null  $clientDataJson
     * @param AuthenticatorData|null  $authenticatorData
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
