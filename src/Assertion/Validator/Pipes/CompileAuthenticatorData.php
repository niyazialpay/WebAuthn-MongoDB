<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\ByteBuffer;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\DataException;

/**
 * @internal
 */
class CompileAuthenticatorData
{
    /**
     * Handle the incoming Assertion Validation.
     *
     * @throws AssertionException
     */
    public function handle(AssertionValidation $validation, Closure $next): mixed
    {
        $data = ByteBuffer::decodeBase64Url($validation->request->json('response.authenticatorData', ''));

        if (! $data) {
            throw AssertionException::make('Authenticator Data does not exist or is empty.');
        }

        try {
            $validation->authenticatorData = AuthenticatorData::fromBinary($data);
        } catch (DataException $e) {
            throw AssertionException::make($e->getMessage());
        }

        return $next($validation);
    }
}
