<?php

namespace niyazialpay\WebAuthn\Assertion\Creator\Pipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use niyazialpay\WebAuthn\Assertion\Creator\AssertionCreation;
use niyazialpay\WebAuthn\Attestation\SessionChallenge;
use Random\RandomException;

class CreateAssertionChallenge
{
    use SessionChallenge;

    /**
     * Create a new pipe instance.
     *
     * @param Repository $config
     */
    public function __construct(protected Repository $config)
    {
        //
    }

    /**
     * Handle the incoming Assertion.
     *
     * @param AssertionCreation $assertion
     * @param Closure $next
     * @return mixed
     * @throws RandomException
     */
    public function handle(AssertionCreation $assertion, Closure $next): mixed
    {
        $options = [];

        if ($assertion->acceptedCredentials?->isNotEmpty()) {
            // @phpstan-ignore-next-line
            $options['credentials'] = $assertion->acceptedCredentials->map->getKey()->toArray();
        }

        $challenge = $this->storeChallenge($assertion->request, $assertion->userVerification, $options);

        $assertion->json->set('challenge', $challenge->data);

        return $next($assertion);
    }
}
