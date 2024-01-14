<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use function parse_url;

abstract class CheckOriginSecure
{
    use ThrowsCeremonyException;

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
     * Handle the incoming WebAuthn Ceremony Validation.
     *
     * @param AttestationValidation|AssertionValidation $validation
     * @param Closure $next
     * @return mixed
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        if (!$validation->clientDataJson->origin) {
            static::throw($validation, 'Response has an empty origin.');
        }

        $origin = parse_url($validation->clientDataJson->origin);

        if (!$origin || !isset($origin['host'], $origin['scheme'])) {
            static::throw($validation, 'Response origin is invalid.');
        }

        if ($origin['host'] !== 'localhost' && $origin['scheme'] !== 'https') {
            static::throw($validation, 'Response not made to a secure server (localhost or HTTPS).');
        }

        return $next($validation);
    }
}
