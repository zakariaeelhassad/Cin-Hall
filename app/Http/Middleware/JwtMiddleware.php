<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Vérifie si un token est présent
            if (!$token = JWTAuth::parseToken()) {
                return response()->json(['status' => 'error', 'message' => 'Token not provided'], 401);
            }

            // Authentifie l'utilisateur
            $user = JWTAuth::authenticate($token);

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['status' => 'error', 'message' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => 'error', 'message' => 'Token is invalid'], 401);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Authorization Token not found'], 401);
        }

        return $next($request);
    }
}