<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use Closure;
use niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation;
use niyazialpay\WebAuthn\Exceptions\AssertionException;

/**
 * @internal
 */
class CheckTypeIsPublicKey
{
    /**
     * Handle the incoming Assertion Validation.
     *
     * @param  \niyazialpay\WebAuthn\Assertion\Validator\AssertionValidation  $validation
     * @param  \Closure  $next
     * @return mixed
     * @throws \niyazialpay\WebAuthn\Exceptions\AssertionException
     */
    public function handle(AssertionValidation $validation, Closure $next): mixed
    {
        if ($validation->request->json('type') !== 'public-key') {
            throw AssertionException::make('Response type is not [public-key].');
        }

        return $next($validation);
    }
}
