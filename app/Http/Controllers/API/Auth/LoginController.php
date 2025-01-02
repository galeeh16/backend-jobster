<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

/**
 * @group Auth
 *
 * APIs for auth
 */
final class LoginController extends Controller
{
    /**
     * Login
     * 
     * @bodyParam email string required. The email user Example: kopnus@example.com
     * @bodyParam password string required. The password user Example: Secret12345
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:3|max:30'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        $validated = $validator->validated();

        try {
            // find user by email
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                return $this->errorResponse(message: 'Unauthenticated, Email or Password are Invalid.', status: 401);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return $this->errorResponse(message: 'Unauthenticated, Email or Password are Invalid.', status: 401);
            }

            $access_token = $this->createJwt($user->id, $user->user_type, $user->is_admin);
            $refresh_token = $this->generateRefreshToken($user->id);

            return $this->successResponse([
                'token_type' => 'Bearer',
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'user' => $user,
            ], 'Login Success', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($this->formatError($e), status: 500);
        }
    }

    private function createJwt(int|string $id, string $user_type, bool $is_admin): string 
    {
        $key = config('app.jwt.key');
        $ttl = config('app.jwt.ttl');

        $payload = [
            'iss' => config('app.name'),// Issuer (opsional)
            'sub' => $id,               // Subject (user ID)
            'iat' => time(),            // Waktu dibuatnya token
            'exp' => time() + $ttl,     // Waktu kadaluwarsa token (30 menit)
            'user_type' => $user_type,  // extra data
            'is_admin' => $is_admin     // extra data
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    private function generateRefreshToken(int|string $id): string
    {
        $key = config('app.jwt.key');
        $ttl = config('app.jwt.refresh_ttl');

        $payload = [
            'sub' => $id,               // Subject (user ID)
            'exp' => time() + $ttl,     // Waktu kadaluwarsa refresh token (12 jam)
            'is_refresh' => true,       // extra data
        ];

        $refresh_token = JWT::encode($payload, $key, 'HS256');

        // simpan ke local cache si refresh tokennya
        $this->saveRefreshTokenToCache($id, $refresh_token);
        return $refresh_token;
    }

    private function saveRefreshTokenToCache($user_id, string $refresh_token): void
    {
        Cache::put('refresh_token_'.$user_id, $refresh_token, config('app.jwt.refresh_ttl')+10);
    }

    private function removeRefreshTokenFromCache($user_id): void 
    {
        Cache::delete('refresh_token_'.$user_id);
    }

    /**
     * Refresh Token
     * 
     * @header Authorization Bearer <token>
     */
    public function refresh(Request $request): JsonResponse
    {
        $authorization = $request->header('Authorization');
        if (!$authorization) {
            return response()->json(['message' => 'Authorization header is required'], 401);
        }

        $token = str_replace('Bearer ' , '', $authorization);
        
        try {
            $jwt = JWT::decode($token, new Key(config('app.jwt.key'), 'HS256'));

            // cek apakah jwt ada property is_refresh, kalo tidak ada berarti bukan refresh token, lihat method generateRefreshToken()
            if (!isset($jwt->is_refresh)) {
                return response()->json(['message' => 'Refresh token signature not valid'], 401);
            }

            $user_id = $jwt->sub;
            $cache = Cache::get('refresh_token_'.$user_id);

            // cek apakah refresh token ada di cache
            if (!$cache) {
                return response()->json(['message' => 'Data refresh token not found'], 401);
            }

            // cek apakah value cache sama dengan refresh token header
            if ($cache !== $token) {
                return response()->json(['message' => 'Refresh token has been blacklist or removed'], 401);
            }

            $user = User::where('id', $user_id)->first();
            if (!$user) {
                return $this->errorResponse(null, 'User not found', 401);
            }

            $access_token = $this->createJwt($user->id, $user->user_type, $user->is_admin);
            $reresh_token = $this->generateRefreshToken($user->id);

            return $this->successResponse([
                'token_type' => 'Bearer',
                'access_token' => $access_token,
                'refresh_token' => $reresh_token,
            ], 'Refresh Token Success', 200);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), 401);
        }
    }


}