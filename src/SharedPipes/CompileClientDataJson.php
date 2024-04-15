<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use JsonException;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\ByteBuffer;
use niyazialpay\WebAuthn\ClientDataJson;

use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @internal
 */
abstract class CompileClientDataJson
{
    use ThrowsCeremonyException;

    /**
     * Handle the incoming WebAuthn Ceremony Validation.
     *
     * @throws AttestationException
     * @throws AssertionException
     */
    public function handle(AssertionValidation|AttestationValidation $validation, Closure $next): mixed
    {
        try {
            $object = json_decode(
                ByteBuffer::decodeBase64Url($validation->request->json('response.clientDataJSON', '')),
                false, 32, JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            static::throw($validation, 'Client Data JSON is invalid or malformed.');
        }

        if (! $object) {
            static::throw($validation, 'Client Data JSON is empty.');
        }

        $object = (object) $object;

        foreach (['type', 'origin', 'challenge'] as $key) {
            if (! isset($object->{$key})) {
                static::throw($validation, "Client Data JSON does not contain the [$key] key.");
            }
        }

        $validation->clientDataJson = new ClientDataJson(
            $object->type, $object->origin, ByteBuffer::fromBase64Url($object->challenge)
        );

        return $next($validation);
    }
}
