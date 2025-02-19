<?php

namespace niyazialpay\WebAuthn\Attestation\Creator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Attestation\Creator\AttestationCreation;

/**
 * @internal
 */
class MayRequireUserVerification
{
    /**
     * Handle the Attestation creation.
     */
    public function handle(AttestationCreation $attestable, Closure $next): mixed
    {
        if ($attestable->userVerification) {
            $attestable->json->set('authenticatorSelection.userVerification', $attestable->userVerification->value);
        }

        return $next($attestable);
    }
}
