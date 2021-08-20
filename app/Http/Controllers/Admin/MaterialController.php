<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MaterialController extends BaseController
{
    public function getMaterialById(Request $request, $m_code = null)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                $materials = MaterialModel::where('m_code', $m_code)
                    ->with('balances')
                    ->with('materialUnit')
                    ->first();

                if ($materials) {
                    // if ($materials->m_exp_date) {
                    //     $materials->m_exp_date = ConvertDateThai::toDateThai($materials->m_exp_date);
                    // }
                    $res->setResponse(200, null, $materials);
                    return response()->json($res->getResponse());
                }
                throw new Exception("ไม่พบวัสดุ");
            } catch (\Throwable $th) {
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            try {
                $search = $request->get('search');

                if ($search != "") {
                    $materials = MaterialModel::where('m_code', 'like', "%$search%")
                        ->orWhere('m_name', 'like', "%$search%")
                        ->orderBy('m_exp_date', 'asc')
                        ->paginate(15);
                } else {
                    $materials = MaterialModel::where('m_code', 'like', "%$search%")
                        ->orWhere('m_name', 'like', "%$search%")
                        ->orderBy('m_name', 'asc')
                        ->paginate(15);
                }

                $html = view('table.stock_out_table_body')->with('materials', $materials)->render();
                return response()->json(['status' => 200, 'data' => $html]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $material = MaterialModel::paginate(15);
            $html = view('table.stock_out_table_body', ['materials' => $material])->render();
            return response()->json(['status' => 200, 'data' => $html]);
        }
    }

}
