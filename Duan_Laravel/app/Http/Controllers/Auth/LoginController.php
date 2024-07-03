<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if ($user->is_delete == 1) {
                throw ValidationException::withMessages([
                    'email' => ['Tài khoản hiện đang bị xóa. Liên hệ admin để làm rõ.'],
                ]);
            }

            if ($user->is_active == 0) {
                throw ValidationException::withMessages([
                    'email' => ['Tài khoản đang bị khóa.'],
                ]);
            }
        }

        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }
}
