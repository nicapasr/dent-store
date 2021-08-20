<?php

namespace App\Http\Controllers\Boards;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\MaterialModel;
use Carbon\Carbon;

class BoardController extends Controller
{
    public function dashboardView()
    {
        try {
            $outOfStock = MaterialModel::where('m_balance', '<', GlobalConstant::$OUTOFSTOCK_AVLUE)
                ->orderBy('m_name', 'asc')
                ->where('m_balance', '>', 0)
                ->get();
            $outOfStocked = MaterialModel::where('m_balance', '=', 0)
                ->orderBy('m_name', 'asc')
                ->get();

            $materialExp = MaterialModel::whereDate('m_exp_date', '>', date('Y-m-d'))
                ->whereDate('m_exp_date', '<=', Carbon::today()->addDays(GlobalConstant::$OUTOFSTOCK_AVLUE))
                ->orderBy('m_exp_date', 'asc')
                ->get();
            $materialExped = MaterialModel::whereDate('m_exp_date', '<=', date('Y-m-d'))
                ->orderBy('m_exp_date', 'asc')
                ->get();

            return view('board.board_home')
                ->with('outOfStock', $outOfStock)
                ->with('outOfStocked', $outOfStocked)
                ->with('materialExp', $materialExp)
                ->with('materialExped', $materialExped);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
