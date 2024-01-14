<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\WebAuthnAuthentication;

/**
 * @method static static forceCreate(array $attributes = [])
 */
class WebAuthnAuthenticatableUser extends User implements WebAuthnAuthenticatable
{
    use HasFactory;
    use WebAuthnAuthentication;

    protected $collection = 'users';
}
