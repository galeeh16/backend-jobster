<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

            $jwt = $this->createJwt($user->id, $user->user_type, $user->is_admin);

            return $this->successResponse([
                'access_token' => $jwt,
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
            'id' => $id,
            'exp' => strtotime('now +' . $ttl . ' minutes'),
            // 'iss' => config('app.url'),
            'user_type' => $user_type,
            'is_admin' => $is_admin
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }


}