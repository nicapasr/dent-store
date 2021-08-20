<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    // public function username()
    // {
    //     return 'username';
    // }

    public function auth(Request $request)
    {
        $message = [
            'username.required' => 'กรุณากรอกรหัสผู้ใช้งาน',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ];

        $valid = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',

        ], $message);

        if ($valid->fails()) {
            return redirect('user/login')
                ->withErrors($valid)
                ->withInput();
        } else {
            $username = $request->username;
            $password = $request->password;
            $remember_me = $request->remember_me;

            if (Auth::attempt(['username' => $username, 'password' => $password], $remember_me)) {
                if (auth()->user()->permission == 1) {
                    return redirect()->intended('/admin/dashboard');
                } else if(auth()->user()->permission == 2) {
                    return redirect()->intended('/board/home');
                } else {
                    return redirect()->intended('/material');
                }
            } else {
                alert()->error('พบผิดพลาด', 'username หรือ password ไม่ถูกต้อง');
                return redirect('user/login')->withInput();
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // protected function redirectTo()
    // {
    //     if (auth()->user()->permission == 1) {
    //         return '/admin/dashboard';
    //     } else {
    //         return '/dashboard';
    //     }
    // }
}
