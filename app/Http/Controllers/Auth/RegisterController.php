<?php

namespace App\Http\Controllers\Auth;

use App\DepartmentModel;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GlobalConstant;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = "login";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        $department = DepartmentModel::all();
        return view('auth.register')->with('deps', $department);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create(Request $request)
    {
        $message = [
            'userName.required' => 'กรุณากรอก Username',
            'userName.unique' => 'Username นี้ถูกใช้งานแล้ว',
            'passWord.required' => 'กรุณากรอก Password',
            'fname.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'lname.required' => 'กรุณากรอกนามสกุลผู้ใช้งาน',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'department.required' => 'กรุณาเลือกสังกัด',
        ];

        $valid = Validator::make($request->all(), [
            'userName' => ['required', 'string', 'max:255', 'unique:user_profiles,username'],
            'passWord' => ['required', 'string', 'min:8'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:0', 'max:10'],
            'department' => ['required', 'integer'],
        ], $message);

        if ($valid->fails()) {
            return redirect('user/register')
                ->withErrors($valid)
                ->withInput();
        }

        $result = User::create([
            'username' => $request->userName,
            'password' => Hash::make($request->passWord),
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'phone' => $request->phone,
            'department' => $request->department,
            'permission' => User::DEFAULT_TYPE,
        ]);

        if ($result) {
            alert()->success(GlobalConstant::$SUCCESS_TITLE, 'สมัครสมาชิกเรียบร้อยแล้ว');
            return redirect()->route('register');
        }

        alert()->error('พบผิดพลาด', 'กรุณาลองใหม่อีกครั้ง');
        return redirect()->route('register');
    }
}
