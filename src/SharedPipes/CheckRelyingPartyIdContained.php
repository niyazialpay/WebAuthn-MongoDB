<?php

namespace niyazialpay\WebAuthn\SharedPipes;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;
use niyazialpay\WebAuthn\Exceptions\AttestationException;
use function hash_equals;
use function parse_url;
use const PHP_URL_HOST;

/**
 * @internal
 */
abstract class CheckRelyingPartyIdContained
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
     * @throws AssertionException
     * @throws AttestationException
     */
    public function handle(AttestationValidation|AssertionValidation $validation, Closure $next): mixed
    {
        if (!$host = parse_url($validation->clientDataJson->origin, PHP_URL_HOST)) {
            static::throw($validation, 'Relying Party ID is invalid.');
        }

        $current = parse_url(
            $this->config->get('webauthn.relying_party.id') ?? $this->config->get('app.url'), PHP_URL_HOST
        );

        // Check the host is the same or is a subdomain of the current config domain.
        if (hash_equals($current, $host) || Str::is("*.$current", $host)) {
            return $next($validation);
        }

        static::throw($validation, 'Relying Party ID not scoped to current.');
    }
}
