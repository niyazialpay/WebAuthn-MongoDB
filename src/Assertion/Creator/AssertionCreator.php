<?php

namespace niyazialpay\WebAuthn\Assertion\Creator;

use Illuminate\Pipeline\Pipeline;

/**
 * @method AssertionCreation thenReturn()
 */
class AssertionCreator extends Pipeline
{
    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected $pipes = [
        Pipes\AddConfiguration::class,
        Pipes\MayRetrieveCredentialsIdForUser::class,
        Pipes\MayRequireUserVerification::class,
        Pipes\CreateAssertionChallenge::class,
    ];
}
