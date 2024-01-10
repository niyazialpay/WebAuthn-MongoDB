<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Challenge;

/**
 * This should be the first pipe to run, as the Challenge may expire by mere milliseconds.
 *
 * @internal
 */
abstract class RetrieveChallenge
{
    use ThrowsCeremonyException;

    /**
     * Create a new pipe instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(protected Repository $config)
    {
        //
    }

    /**
     * Handle the incoming Assertion Validation.
     *
     * @param  \niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation|\niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation  $validation
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        $validation->challenge = $this->retrieveChallenge($validation->request);

        if (!$validation->challenge) {
            static::throw($validation, 'Challenge does not exist.');
        }

        return $next($validation);
    }

    /**
     * Pulls an Attestation challenge from the Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \niyazialpay\WebAuthn\Challenge|null
     */
    protected function retrieveChallenge(Request $request): ?Challenge
    {
        /** @var \niyazialpay\WebAuthn\Challenge|null $challenge */
        $challenge = $request->session()->pull($this->config->get('webauthn.challenge.key'));

        if (!$challenge || $challenge->hasExpired()) {
            return null;
        }

        return $challenge;
    }
}
