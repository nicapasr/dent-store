<?php

namespace App\Http\Controllers\Users;

use App\DepartmentModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\LineNotiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use KS\Line\LineNotify;

class AccountDetailController extends Controller
{
    public function index()
    {
        $line = new LineNotiController;
        $auth_line = $line->auth_line();
        $departments = DepartmentModel::all();
        return view('account_detail')->with('departments', $departments)->with('line', $auth_line);
    }

    public function updateProfile(Request $request)
    {
        $message = [
            'phone' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.min:10' => 'กรุณากรอกเบอร์โทรศัพท์ให้ครบ 10 หลัก',
            'first_name.required' => 'กรุณากรอกชื่อ',
            'last_name.required' => 'กรุณากรอกนามสกุล',
            'department' => 'กรุณาเลือกสังกัด'
        ];

        $valid = Validator::make($request->all(), [
            'phone' => 'required|string|min:10',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'department' => 'required|integer'
        ], $message);

        if ($valid->fails()) {
            return redirect()->route('user.detail')->withErrors($valid)->withInput();
        }

        $username = $request->username;
        $user = User::where('username', $username)->first();

        if ($user) {
            $user->phone = $request->phone;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->department = $request->department;
            $result = $user->save();

            if ($result) {
                alert()->success(GlobalConstant::$SUCCESS_TITLE, 'แก้ไขข้อมูลเรียบร้อยแล้ว');
                return redirect()->route('user.detail');
            }
            alert()->error('พบพบผิดพลาด', 'กรุณาลองใหม่อีกครั้ง');
            return redirect()->route('user.detail');
        }
        alert()->error('พบพบผิดพลาด', 'ไม่พบบัญชีผู้ใช้ ' . $username);
        return redirect()->route('user.detail');
    }
}
