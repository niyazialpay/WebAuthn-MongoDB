<?php

namespace niyazialpay\WebAuthn\Assertion\Validator\Pipes;

use niyazialpay\WebAuthn\SharedPipes\CheckOriginSecure as BaseCheckOriginSame;

/**
 * 9. Verify that the value of C.origin matches the Relying Party's origin.
 *
 * @internal
 */
class CheckOriginSecure extends BaseCheckOriginSame
{
    //
}
