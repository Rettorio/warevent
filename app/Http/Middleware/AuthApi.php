<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new JWTException('User not found');
            }
        } catch (TokenExpiredException $e) {
            // Refresh expired token
            try {
                $newToken = JWTAuth::refresh();
                return response()->json(['message' => 'Token expired', 'new_token' => $newToken], 401);
            } catch (JWTException $e) {
                return response()->json(['message' => 'Could not refresh token'], 401);
            }
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Missing or invalid token'], 401);
        }

        return $next($request);
    }
}
