<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\WebAuthnAuthentication;

/**
 * @method static static forceCreate(array $attributes = [])
 */
class WebAuthnAuthenticatableUser extends User implements WebAuthnAuthenticatable
{
    use HasFactory;
    use WebAuthnAuthentication;

    protected $table = 'users';
}
