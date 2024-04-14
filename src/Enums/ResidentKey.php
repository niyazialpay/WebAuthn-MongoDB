<?php

namespace niyazialpay\WebAuthn\Enums;

enum ResidentKey: string
{
    case REQUIRED = 'required';
    case PREFERRED = 'preferred';
    case DISCOURAGED = 'discouraged';
}
