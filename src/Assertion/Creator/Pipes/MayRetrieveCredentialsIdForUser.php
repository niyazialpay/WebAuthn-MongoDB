<?php

namespace niyazialpay\WebAuthn\Assertion\Creator\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use niyazialpay\WebAuthn\Assertion\Creator\AssertionCreation;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;
use function array_filter;

class MayRetrieveCredentialsIdForUser
{
    /**
     * Handle the incoming Assertion.
     *
     * @param AssertionCreation $assertion
     * @param Closure $next
     * @return mixed
     */
    public function handle(AssertionCreation $assertion, Closure $next): mixed
    {
        // If there is a user found, we will pluck the IDS and add them as a binary buffer.
        if ($assertion->user) {
            $assertion->acceptedCredentials = $assertion->user->webAuthnCredentials()->get(['id', 'transports']);

            if ($assertion->acceptedCredentials->isNotEmpty()) {
                $assertion->json->set('allowCredentials', $this->parseCredentials($assertion->acceptedCredentials));
            }
        }

        return $next($assertion);
    }

    /**
     * Adapt all credentials into an `allowCredentials` digestible array.
     *
     * @param EloquentCollection<int, WebAuthnCredential> $credentials
     * @return Collection<int, array{id?: mixed, type: string, transports?: non-empty-array<int, string>}>
     */
    protected function parseCredentials(EloquentCollection $credentials): Collection
    {
        // @phpstan-ignore-next-line
        return $credentials->map(static function (WebAuthnCredential $credential): array {
            return array_filter([
                'id' => $credential->getKey(),
                'type' => 'public-key',
                'transports' => $credential->transports
            ]);
        });
    }
}
