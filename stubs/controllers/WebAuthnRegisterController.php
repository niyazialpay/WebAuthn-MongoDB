<?php

namespace App\Http\Controllers\WebAuthn;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use niyazialpay\WebAuthn\Http\Requests\AttestationRequest;
use niyazialpay\WebAuthn\Http\Requests\AttestedRequest;
use function response;

class WebAuthnRegisterController
{
    /**
     * Returns a challenge to be verified by the user device.
     *
     * @param AttestationRequest $request
     * @return Responsable
     */
    public function options(AttestationRequest $request): Responsable
    {
        return $request
            ->fastRegistration()
//            ->userless()
//            ->allowDuplicates()
            ->toCreate();
    }

    /**
     * Registers a device for further WebAuthn authentication.
     *
     * @param AttestedRequest $request
     * @return Response
     */
    public function register(AttestedRequest $request): Response
    {
        $request->save();

        return response()->noContent();
    }
}
