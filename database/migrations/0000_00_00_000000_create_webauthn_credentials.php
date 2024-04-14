<?php

use Illuminate\Database\Schema\Blueprint;
use niyazialpay\WebAuthn\Models\WebAuthnCredential;

return WebAuthnCredential::migration(function (Blueprint $table) {
    // Here you can add custom columns to the Two Factor table.
    //
    // $table->string('alias')->nullable();
});
