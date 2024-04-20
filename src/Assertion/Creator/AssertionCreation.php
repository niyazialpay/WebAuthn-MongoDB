<?php

namespace niyazialpay\WebAuthn\Assertion\Creator;


use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Enums\UserVerification;
use niyazialpay\WebAuthn\JsonTransport;

class AssertionCreation
{
    /**
     * Create a new Assertion Creation instance.
     */
    public function __construct(
        public Request $request,
        public ?WebAuthnAuthenticatable $user = null,
        public ?Collection $acceptedCredentials = null,
        public ?UserVerification $userVerification = null,
        public JsonTransport $json = new JsonTransport(),
    ) {
        //
    }
}
