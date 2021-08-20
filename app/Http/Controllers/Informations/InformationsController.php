<?php

namespace App\Http\Controllers\Informations;

use App\DepartmentModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\MaterialTypeModel;
use App\MemberModel;
use App\UnitModel;
use App\User;
use App\WareHouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class InformationsController extends Controller
{
    public function index()
    {
        $users = User::orderBy('username', 'asc')->paginate(20);
        $warehouse = WareHouseModel::orderBy('warehouse_name', 'asc')->paginate(20);
        $unit = UnitModel::orderBy('unit_name', 'asc')->paginate(20);
        $unitAll = UnitModel::all();
        $materials = MaterialModel::orderBy('m_name', 'asc')->groupBy('m_code')->paginate(20);
        $member = MemberModel::orderBy('fname', 'asc')->paginate(20);
        $date = Carbon::now();

        return view('admin.informations.informations')
            ->with('users', $users)
            ->with('warehouse', $warehouse)
            ->with(('units'), $unit)
            ->with(('unitAll'), $unitAll)
            ->with('materials', $materials)
            ->with('members', $member)
            ->with('date', $date);
    }

    public function addUser(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $valid = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string|min:8',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'phone' => 'required|string',
                'permission' => 'required|string',
            ]);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $user = new User;
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->first_name = $request->firstName;
                $user->last_name = $request->lastName;
                $user->phone = $request->phone;
                $user->permission = $request->permission;
                $user->save();

                DB::commit();

                $res->setResponse(200, null, $user);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }
}
