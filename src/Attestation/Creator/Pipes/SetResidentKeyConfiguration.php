<?php

namespace niyazialpay\WebAuthn\Attestation\Creator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Attestation\Creator\AttestationCreation;
use niyazialpay\WebAuthn\Enums\ResidentKey;
use niyazialpay\WebAuthn\Enums\UserVerification;

/**
 * @internal
 */
class SetResidentKeyConfiguration
{
    /**
     * Handle the Attestation creation.
     */
    public function handle(AttestationCreation $attestable, Closure $next): mixed
    {
        if ($attestable->residentKey) {
            $attestable->json->set('authenticatorSelection.residentKey', $attestable->residentKey->value);

            $verifiesUser = $attestable->residentKey === ResidentKey::REQUIRED;

            $attestable->json->set('authenticatorSelection.requireResidentKey', $verifiesUser);

            if ($verifiesUser) {
                $attestable->userVerification = UserVerification::REQUIRED;
            }
        }

        return $next($attestable);
    }
}
