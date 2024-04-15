<?php

namespace niyazialpay\WebAuthn\Attestation;

use Illuminate\Http\Request;
use niyazialpay\WebAuthn\Challenge;
use niyazialpay\WebAuthn\Enums\UserVerification;
use Random\RandomException;

trait SessionChallenge
{
    /**
     * Stores an Attestation challenge into the Cache.
     *
     * @param  array{credentials?: string[]}|array{user_uuid: string, user_handle: string}  $options
     *
     * @throws RandomException
     */
    protected function storeChallenge(Request $request, ?UserVerification $verify, array $options = []): Challenge
    {
        $challenge = $this->createChallenge($verify, $options);

        $request->session()->put($this->config->get('webauthn.challenge.key'), $challenge);

        return $challenge;
    }

    /**
     * Creates a Challenge using the default timeout.
     *
     * @param  array{credentials?: string[]}|array{user_uuid: string, user_handle: string}  $options
     *
     * @throws RandomException
     */
    protected function createChallenge(?UserVerification $verify, array $options = []): Challenge
    {
        return Challenge::random(
            $this->config->get('webauthn.challenge.bytes'),
            $this->config->get('webauthn.challenge.timeout'),
            $verify === UserVerification::REQUIRED,
            $options,
        );
    }
}
