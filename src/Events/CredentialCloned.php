<?php

namespace niyazialpay\WebAuthn\Events;

use Illuminate\Foundation\Events\Dispatchable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

class CredentialCloned
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param WebAuthnCredential $credential
     * @param  int  $reportedCount  The counter reported by the user authenticator.
     */
    public function __construct(public WebAuthnCredential $credential, public int $reportedCount)
    {
        //
    }
}
