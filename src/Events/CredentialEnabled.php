<?php

namespace niyazialpay\WebAuthn\Events;

use Illuminate\Foundation\Events\Dispatchable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class CredentialEnabled
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param  \niyazialpay\WebAuthn\Models\WebAuthnCredential  $credential
     */
    public function __construct(public WebAuthnCredential $credential)
    {
        //
    }
}
