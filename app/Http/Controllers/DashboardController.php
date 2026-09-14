<?php

namespace App\Http\Controllers;

use App\Models\Pop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPop = Pop::count();

        return view('dashboard', compact('totalPop'));
    }

    // AJAX — live search saat user mengetik
    public function searchPop(Request $request)
    {
        $q = $request->query('q', '');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $pops = Pop::where('nama_pop', 'like', "%{$q}%")
            ->orWhere('kode_pop', 'like', "%{$q}%")
            ->limit(8)
            ->get(['id', 'nama_pop', 'kode_pop', 'kota_kabupaten']);

        return response()->json($pops);
    }

    // AJAX — begitu user pilih 1 POP dari saran, ambil ringkasan lengkapnya
    public function popSummary(Pop $pop)
    {
        $rectifiers = $pop->rectifiers()->with('modules')->get();
        $kwhs = $pop->kwhs()->get();

        return view('dashboard.partials.pop-summary', compact('pop', 'rectifiers', 'kwhs'));
    }
}
