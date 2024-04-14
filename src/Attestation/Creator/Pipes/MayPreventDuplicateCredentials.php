<?php

namespace niyazialpay\WebAuthn\Attestation\Creator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Attestation\Creator\AttestationCreation;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

/**
 * @internal
 */
class MayPreventDuplicateCredentials
{
    /**
     * Handle the Attestation creation.
     */
    public function handle(AttestationCreation $attestable, Closure $next): mixed
    {
        if ($attestable->uniqueCredentials) {
            $attestable->json->set('excludeCredentials', $this->credentials($attestable->user));
        }

        return $next($attestable);
    }

    /**
     * Returns a collection of credentials ready to be inserted into the Attestable JSON.
     */
    protected function credentials(WebAuthnAuthenticatable $user): array
    {
        return $user
            ->webAuthnCredentials()
            ->get(['id', 'transports'])
            // @phpstan-ignore-next-line
            ->map(static function (WebAuthnCredential $credential): array {
                return array_filter([
                    'id' => $credential->getKey(),
                    'type' => 'public-key',
                    'transports' => $credential->transports,
                ]);
            })
            ->toArray();
    }
}
