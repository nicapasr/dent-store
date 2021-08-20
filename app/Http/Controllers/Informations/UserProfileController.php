<?php

namespace App\Http\Controllers\Informations;

use App\DepartmentModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\Response;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        //
        return view('users.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        //
        $dep = DepartmentModel::orderby('dep_name')->get();
        return view('users.register')->with('departments', $dep);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertUser(Request $request)
    {
        $userModel = new User;
        $userModel->user_name = $request->userName;
        $userModel->password = Hash::make($request->passWord);
        $userModel->permission = $request->permission;
        $userModel->first_name = $request->fname;
        $userModel->last_name = $request->lname;
        $userModel->phone = $request->phone;
        $userModel->department = $request->department;
        $response = $userModel->save();

        if ($response != null) {
            return redirect('users/login');
        } else {
            return redirect('users/register')->with('error');
        }

        // return redirect('users/login');
    }

    public function registerUser(Request $request)
    {
        $message = [
            'userName.required' => 'กรุณากรอก Username',
            'passWord.required' => 'กรุณากรอก Password',
            'fname.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'lname.required' => 'กรุณากรอกนามสกุลผู้ใช้งาน',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'department.required' => 'กรุณาเลือกสังกัด',
        ];

        $valid = Validator::make($request->all(), [
            'userName' => 'required',
            'passWord' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'department' => 'required',
            // 'm_price_unit' => 'required|integer|min:0',
            // 'm_in' => 'required|integer|min:0',

        ], $message);

        if ($valid->fails()) {
            return redirect('/user/register')
                ->withErrors($valid)
                ->withInput();
        } else {

            // $temp = UserProfile::where('user_name', $request->user_name)->first();

            // if ($temp === null) {
            //     $userModel = new UserProfile;
            //     $userModel->user_name = $request->user_name;
            //     $userModel->password = Hash::make($request->password);
            //     $userModel->permission = $request->permission;
            //     $userModel->first_name = $request->first_name;
            //     $userModel->last_name = $request->last_name;
            //     $userModel->phone = $request->phone;
            //     $userModel->department = $request->department;
            //     $response = $userModel->save();

            //     if ($response != null) {
            //         return response()->json(['status' => '0', 'massage' => 'เพิ่มผู้ใช้งานสำเร็จ', 'data' => $userModel]);
            //     } else {
            //         return response()->json(['status' => '1', 'massage' => 'เพิ่มผู้ใช้งานไม่สำเร็จ']);
            //     }
            // } else {
            //     return response()->json(['status' => '2', 'massage' => 'มีผู้ใช้งานนี้อยู่แล้ว']);
            // }
        }
    }

    public function updateUserProfile(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make($request->all(), [
                'username' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|string',
                'permission' => 'required',
            ]);

            if ($valid->fails()) {
                return response()->json(['status' => '1', 'massage' => 'Validator errors', 'data' => $valid->errors()]);
            }

            try {
                DB::beginTransaction();

                $username = $request->username;
                $userModel = User::where("username", $username)->first();

                if (!$userModel) {
                    return response()->json(['status' => '2', 'massage' => 'ไม่พบบัญชีผู้ใช้งานนี้']);
                }

                $userModel->first_name = $request->first_name;
                $userModel->last_name = $request->last_name;
                $userModel->phone = $request->phone;
                $userModel->permission = $request->permission;
                $userModel->save();

                DB::commit();

                return response()->json(['status' => '0', 'massage' => 'แก้ไขสำเร็จ', 'data' => $userModel]);
            } catch (\Throwable $th) {
                //throw $th;

                DB::rollback();
                return response()->json(['status' => '3', 'massage' => $th->getMessage()]);
            }
        }
    }
}
