<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use DomainException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use InvalidArgumentException;
use UnexpectedValueException;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws InvalidArgumentException     Provided key/key-array was empty or malformed
     * @throws DomainException              Provided JWT is malformed
     * @throws UnexpectedValueException     Provided JWT was invalid
     * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('Authorization');
        if (!$authorization) {
            return response()->json(['message' => 'Authorization header is required'], 401);
        }

        $token = str_replace('Bearer ' , '', $authorization);

        try {
            $jwt = JWT::decode($token, new Key(config('app.jwt.key'), 'HS256'));
            $id = $jwt->id; 
            Auth::loginUsingId($id);  

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'data' => null], 401);
        } 
    }
}
