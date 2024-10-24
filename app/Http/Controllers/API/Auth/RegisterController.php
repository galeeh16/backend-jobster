<?php declare(strict_types=1);

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * @group Auth
 *
 * APIs for auth
 */
final class RegisterController extends Controller
{
    /**
     * Register a user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users,email',
            'name' => 'required|string|min:3|max:255',
            'user_type' => 'required|string|in:company,applier',
            'password' => ['required', 'string', Password::min(3)->max(30)->letters()->numbers()->mixedCase()],
            'confirm_password' => 'required|same:password',
        ]); 

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
        }

        try {
            $validated = $validator->validated();

            $user = User::create([
                'email' => $validated['email'],
                'name' => $validated['name'],
                'password' => bcrypt($validated['password']),
                'user_type' => $validated['user_type'],
                'is_admin' => false,
            ]);

            return $this->successResponse(new UserResource($user), 'Success Register', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($this->formatError($e));
        }
    }
}