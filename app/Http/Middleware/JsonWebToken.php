<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class JsonWebToken
{
    /**
     * Extract a JWT payload and place it into the request's attributes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string)$request->bearerToken();
        try {
            $payload = JWT::decode($token, $this->getJwtKey());
        }
        catch (LogicException $e) {
            Log::error('JsonWebToken middleware -- Internal server error', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (UnexpectedValueException $e) {
            Log::warning('JsonWebToken middleware -- Unauthorized', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->add(['jwt' => $payload]);

        return $next($request);
    }

    /**
     * Construct the JWT key from the config/environment.
     *
     * N.B. This requires the following added to /config/auth.php
     *
     *   'jwt' => [
     *     'key' => env('JWT_KEY'),
     *     'algorithm' => 'HS256',      // or suitable ...
     *   ],
     */
    private function getJwtKey(): Key
    {
        return new Key(config('auth.jwt.key'), config('auth.jwt.algorithm'));
    }
}
