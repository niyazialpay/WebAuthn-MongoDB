<?php

namespace niyazialpay\WebAuthn\Events;

use Illuminate\Foundation\Events\Dispatchable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class CredentialDisabled
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public WebAuthnCredential $credential)
    {
        //
    }
}
