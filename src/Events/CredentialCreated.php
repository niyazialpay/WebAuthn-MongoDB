<?php

namespace niyazialpay\WebAuthn\Events;

use Illuminate\Foundation\Events\Dispatchable;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class CredentialCreated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param  \niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable  $user
     * @param  \niyazialpay\WebAuthn\Models\WebAuthnCredential  $credential
     */
    public function __construct(public WebAuthnAuthenticatable $user, public WebAuthnCredential $credential)
    {
        //
    }
}
