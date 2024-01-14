<?php

namespace niyazialpay\WebAuthn\Assertion\Creator\Pipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use niyazialpay\WebAuthn\Assertion\Creator\AssertionCreation;

class AddConfiguration
{
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
     */
    public function handle(AssertionCreation $assertion, Closure $next): mixed
    {
        $assertion->json->set('timeout', $this->config->get('webauthn.challenge.timeout') * 1000);

        return $next($assertion);
    }
}
