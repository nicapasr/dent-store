<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\MaterialModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        if (auth()->user()->permission == 1) {
            return redirect()->route('admin.dashboard');
        }else if (auth()->user()->permission == 2) {
            return redirect()->route('board.home');
        }else {
            return redirect()->route('user.material.home.view');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $material = MaterialModel::where('m_name', 'like', "%$search%")
            ->orWhere('m_code', 'like', "%$search%")
            ->orWhere('m_type', 'like', "%$search%")
            ->paginate(10);
        return view('home')->with('material', $material)->with('search', $search);
    }
}
