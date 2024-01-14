<?php

namespace niyazialpay\WebAuthn\Exceptions;

use Illuminate\Validation\ValidationException;
use niyazialpay\WebAuthn\Contracts\WebAuthnException;

class AssertionException extends ValidationException implements WebAuthnException
{
    /**
     * Create a new Assertion Exception with the error message.
     *
     * @param  string  $message
     * @return static
     */
    public static function make(string $message): static
    {
        return static::withMessages(['assertion' => "Assertion Error: $message"]);
    }
}
