<?php

namespace niyazialpay\WebAuthn\Attestation;

use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Challenge;
use niyazialpay\WebAuthn\WebAuthn;

trait SessionChallenge
{
    /**
     * Stores an Attestation challenge into the Cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $verify
     * @param  array  $options
     * @return \niyazialpay\WebAuthn\Challenge
     */
    protected function storeChallenge(Request $request, ?string $verify, array $options = []): Challenge
    {
        $challenge = $this->createChallenge($verify, $options);

        $request->session()->put($this->config->get('webauthn.challenge.key'), $challenge);

        return $challenge;
    }

    /**
     * Creates a Challenge using the default timeout.
     *
     * @param  string|null  $verify
     * @param  array  $options
     * @return \niyazialpay\WebAuthn\Challenge
     */
    protected function createChallenge(?string $verify, array $options = []): Challenge
    {
        return Challenge::random(
            $this->config->get('webauthn.challenge.bytes'),
            $this->config->get('webauthn.challenge.timeout'),
            $verify === WebAuthn::USER_VERIFICATION_REQUIRED,
            $options,
        );
    }
}
