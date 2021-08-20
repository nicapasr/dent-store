<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use App\MemberModel;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function addMember(Request $request)
    {
        if ($request->fname && $request->lname) {
            $member = MemberModel::where('fname', $request->fname)->where('lname', $request->lname)->first();
            if ($member === null) {
                $memberModel = new MemberModel;
                $memberModel->fname = $request->fname;
                $memberModel->lname = $request->lname;
                $result = $memberModel->save();

                if ($result != null) {
                    return response()->json(['status' => '0', 'massage' => 'เพิ่มสมาชิกสำเร็จ', 'data' => $memberModel]);
                } else {
                    return response()->json(['status' => '1', 'massage' => 'เพิ่มสมาชิกไม่สำเร็จ']);
                }
            } else {
                return response()->json(['status' => '2', 'massage' => 'มีข้อมูลของรายชื่อสมาชิกนี้อยู่ในระบบแล้ว']);
            }
        }
    }
    public function editMember(Request $request)
    {
        if ($request->id && $request->fname && $request->lname) {
            $member = MemberModel::where('id', $request->id)->first();
            if ($member != null) {
                $member->fname = $request->fname;
                $member->lname = $request->lname;
                $result = $member->save();

                if ($result != null) {
                    return response()->json(['status' => '0', 'massage' => 'แก้ไขสำเร็จ', 'data' => $member]);
                } else {
                    return response()->json(['status' => '1', 'massage' => 'แก้ไขไม่สำเร็จ']);
                }
            }
            return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
        }
    }

    public function deleteMember(Request $request)
    {
        if ($request->id) {
            $member = MemberModel::where('id', $request->id)->first();
            if ($member != null) {
                $result = $member->delete();
                if ($result != null) {
                    return response()->json(['status' => '0', 'massage' => 'ลบสมาชิกสำเร็จ', 'data' => $member]);
                } else {
                    return response()->json(['status' => '1', 'massage' => 'ลบสมาชิกไม่สำเร็จ']);
                }
            } else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }
}
