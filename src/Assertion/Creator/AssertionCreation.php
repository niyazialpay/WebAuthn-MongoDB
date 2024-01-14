<?php

namespace niyazialpay\WebAuthn\Assertion\Creator;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\JsonTransport;

class AssertionCreation
{
    /**
     * The Json Transport helper to build the message.
     *
     * @var JsonTransport
     */
    public JsonTransport $json;

    /**
     * Create a new Assertion Creation instance.
     *
     * @param Request $request
     * @param WebAuthnAuthenticatable|null  $user
     * @param Collection|null  $acceptedCredentials
     * @param  string|null  $userVerification
     */
    public function __construct(
        public Request $request,
        public ?WebAuthnAuthenticatable $user = null,
        public ?Collection $acceptedCredentials = null,
        public ?string $userVerification = null,
    )
    {
        $this->json = new JsonTransport();
    }
}
