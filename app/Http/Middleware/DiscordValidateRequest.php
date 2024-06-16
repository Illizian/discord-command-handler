<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DiscordValidateRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicKey = config('services.discord.public_key');
        $signature = $request->header('X-Signature-Ed25519');
        $timestamp = $request->header('X-Signature-Timestamp');
        $body = $request->getContent();

        if (! $publicKey || ! $signature || ! $timestamp || ! $body) {
            throw new UnauthorizedHttpException('Unable to validate Discord header');
        }

        try {
            $verified = sodium_crypto_sign_verify_detached(
                hex2bin($signature),
                sprintf('%s%s', $timestamp, $body),
                hex2bin($publicKey)
            );
        } catch (\SodiumException) {
            throw new UnauthorizedHttpException('Unable to validate Discord header');
        }

        if (! $verified) {
            throw new UnauthorizedHttpException('Unable to validate Discord header');
        }

        return $next($request);
    }
}
