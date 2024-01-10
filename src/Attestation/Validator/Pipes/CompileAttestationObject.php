<?php

namespace niyazialpay\WebAuthn\Attestation\Validator\Pipes;

use Closure;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use niyazialpay\WebAuthn\Attestation\AttestationObject;
use niyazialpay\WebAuthn\Attestation\AuthenticatorData;
use niyazialpay\WebAuthn\Attestation\Formats\None;
use niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation;
use niyazialpay\WebAuthn\ByteBuffer;
use niyazialpay\WebAuthn\CborDecoder;
use niyazialpay\WebAuthn\Exceptions\AttestationException;
use niyazialpay\WebAuthn\Exceptions\DataException;
use function base64_decode;
use function is_array;
use function is_string;

/**
 * 12. Perform CBOR decoding on the attestationObject field of the AuthenticatorAttestationResponse
 *     structure to obtain the attestation statement format fmt, the authenticator data authData,
 *     and the attestation statement attStmt.
 *
 * 18. Determine the attestation statement format by performing a USASCII case-sensitive match on
 *     fmt against the set of supported WebAuthn Attestation Statement Format Identifier values.
 *
 * @see https://www.w3.org/TR/webauthn-2/#sctn-registering-a-new-credential
 *
 * @internal
 */
class CompileAttestationObject
{
    /**
     * Handle the incoming Attestation Validation.
     *
     * @param  \niyazialpay\WebAuthn\Attestation\Validator\AttestationValidation  $validation
     * @param  \Closure  $next
     * @return mixed
     * @throws \niyazialpay\WebAuthn\Exceptions\AttestationException
     */
    public function handle(AttestationValidation $validation, Closure $next): mixed
    {
        $data = $this->decodeCborBase64($validation->request);

        // Here we would receive the attestation formats and decode them. Since we're
        // only support the universal "none" we can just check if it's equal or not.
        // Later we may support multiple authenticator formats through a PHP match.
        if ($data['fmt'] !== 'none') {
            throw AttestationException::make("Format name [{$data['fmt']}] is invalid.");
        }

        try {
            $authenticatorData = AuthenticatorData::fromBinary($data['authData']->getBinaryString());
        } catch (DataException $e) {
            throw AttestationException::make($e->getMessage());
        }

        $validation->attestationObject = new AttestationObject(
            $authenticatorData, new None($data, $authenticatorData), $data['fmt']
        );

        return $next($validation);
    }

    /**
     * Returns an array map from a BASE64 encoded CBOR string.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws \niyazialpay\WebAuthn\Exceptions\AttestationException
     */
    #[ArrayShape(["fmt" => "string", "attStmt" => "array", "authData" => ByteBuffer::class])]
    protected function decodeCborBase64(Request $request): array
    {
        try {
            $data = CborDecoder::decode(base64_decode($request->json('response.attestationObject', '')));
        } catch (DataException $e) {
            throw AttestationException::make($e->getMessage());
        }

        if (!is_array($data)) {
            throw AttestationException::make('CBOR Object is anything but an array.');
        }

        if (!isset($data['fmt']) || !is_string($data['fmt'])) {
            throw AttestationException::make('Format is missing or invalid.');
        }

        if (!isset($data['attStmt']) || !is_array($data['attStmt'])) {
            throw AttestationException::make('Statement is missing or invalid.');
        }

        if (!isset($data['authData']) || !$data['authData'] instanceof ByteBuffer) {
            throw AttestationException::make('Authenticator Data is missing or invalid.');
        }

        return $data;
    }
}
