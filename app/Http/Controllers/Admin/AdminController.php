<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function dashboard()
    {
        try {
            $outOfStock = BalanceModel::where('b_value', '<', GlobalConstant::$OUTOFSTOCK_AVLUE)
                          ->where('b_value', '>', 0)
                          ->with('materials')
                          ->whereHas('materials', function($q){
                              $q->orderBy('m_name','asc');
                            })
                          ->get();

            $outOfStocked = BalanceModel::where('b_value', '<', GlobalConstant::$OUTOFSTOCK_AVLUE)
                            ->where('b_value', '=', 0)
                            ->with('materials')
                            ->whereHas('materials', function($q){
                                $q->orderBy('m_name','asc');
                            })
                            ->get();

            $materialExp = BalanceModel::whereDate('b_exp_date', '>', date('Y-m-d'))
                           ->whereDate('b_exp_date', '<=', Carbon::today()->addDays(GlobalConstant::$OUTOFSTOCK_AVLUE))
                           ->with('materials')
                           ->orderBy('b_exp_date', 'asc')
                           ->get();
            $materialExped = BalanceModel::whereDate('b_exp_date', '<=', date('Y-m-d'))
                             ->with('materials')
                             ->orderBy('b_exp_date', 'asc')
                             ->get();

            return view('admin.admin_dashboard')
                ->with('outOfStock', $outOfStock)
                ->with('outOfStocked', $outOfStocked)
                ->with('materialExp', $materialExp)
                ->with('materialExped', $materialExped);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json($th->getMessage());
        }

        // try {
        //     $outOfStock = MaterialModel::where('m_balance', '<', GlobalConstant::$OUTOFSTOCK_AVLUE)
        //         ->orderBy('m_name', 'asc')
        //         ->where('m_balance', '>', 0)
        //         ->get();
        //     $outOfStocked = MaterialModel::where('m_balance', '=', 0)
        //         ->orderBy('m_name', 'asc')
        //         ->get();

        //     $materialExp = MaterialModel::whereDate('m_exp_date', '>', date('Y-m-d'))
        //         ->whereDate('m_exp_date', '<=', Carbon::today()->addDays(GlobalConstant::$OUTOFSTOCK_AVLUE))
        //         ->orderBy('m_exp_date', 'asc')
        //         ->get();
        //     $materialExped = MaterialModel::whereDate('m_exp_date', '<=', date('Y-m-d'))
        //         ->orderBy('m_exp_date', 'asc')
        //         ->get();

        //     return view('admin.admin_dashboard')
        //         ->with('outOfStock', $outOfStock)
        //         ->with('outOfStocked', $outOfStocked)
        //         ->with('materialExp', $materialExp)
        //         ->with('materialExped', $materialExped);
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }
}
