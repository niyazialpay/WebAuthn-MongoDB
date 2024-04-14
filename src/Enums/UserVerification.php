<?php

namespace niyazialpay\WebAuthn\Enums;

enum UserVerification: string
{
    case PREFERRED = 'preferred';
    case DISCOURAGED = 'discouraged';
    case REQUIRED = 'required';
}
