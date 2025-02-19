<?php

namespace Tests;

use niyazialpay\WebAuthn\WebAuthnServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [WebAuthnServiceProvider::class];
    }
}
