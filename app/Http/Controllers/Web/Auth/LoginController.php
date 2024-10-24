<?php declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class LoginController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:3|max:30'
        ]);

        if ($validator->fails()) {
            // return $this->errorResponse($validator->getMessageBag(), 'Invalid Data', 422);
            return back()->withErrors($validator->errors());
        }

        $validated = $validator->validated();

        try {
            // find user by email
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                return back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
                // return $this->errorResponse(message: 'Unauthenticated, Email or Password are Invalid.', status: 401);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['password' => 'Password tidak valid'])->withInput();
            }
            
            Auth::login($user);

            return redirect()->route('home');
        } catch (Exception $e) {
            return $this->errorResponse($this->formatError($e), status: 500);
        }
    }
}