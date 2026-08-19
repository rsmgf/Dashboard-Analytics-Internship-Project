<?php
namespace App\Http\Controllers;

use App\Models\Pop;
use Illuminate\Http\Request;
use App\Http\Requests\StorePopRequest;
use App\Http\Requests\UpdatePopRequest;

class PopController extends Controller
{
    // 1. Menampilkan semua daftar POP (dengan Search & Pagination)
public function index(Request $request)
    {
        // Mulai merangkai query
        $query = Pop::query();

        // Jika ada parameter 'search' di URL (misal: /pops?search=Bungo)
        // Jika ada parameter 'search' di URL
        if ($request->has('search')) {
            $search = $request->input('search');
            
            $query->where('nama_pop', 'like', "%{$search}%")
                    ->orWhere('kode_pop', 'like', "%{$search}%")
                    ->orWhere('kota_kabupaten', 'like', "%{$search}%")
                    ->orWhere('jenis_bangunan', 'like', "%{$search}%"); 
        }

        // Ambil data dengan Pagination (misal: 10 data per halaman)
        $pops = $query->paginate(10)->appends($request->query());

        // KEMBALIKAN KE VIEW, BUKAN JSON
        return view('list-pop', compact('pops'));
    }

    // 2. Menyimpan data POP baru
    public function store(StorePopRequest $request) // Ubah di sini
    {
        // Validasi otomatis dijalankan sebelum kode ini dieksekusi.
        // Cukup ambil data yang sudah tervalidasi:
        $validated = $request->validated();

        $pop = Pop::create($validated);

        return response()->json([
            'message' => 'Data POP berhasil disimpan!',
            'data'    => $pop
        ], 201);
    }

    // 3. Menampilkan detail satu POP (beserta relasinya nanti jika diperlukan)
    public function show($id)
    {
        $pop = Pop::findOrFail($id);

        return response()->json([
            'message' => 'Detail POP',
            'data'    => $pop
        ], 200);
    }

    // 4. Edit data POP
    public function update(UpdatePopRequest $request, $id)
    {
        $pop = Pop::findOrFail($id);
        
        $validated = $request->validated();
        
        $pop->update($validated);

        return response()->json([
            'message' => 'Data POP berhasil diperbarui!',
            'data'    => $pop
        ], 200);
    }

    // 5. Menghapus data POP
    public function destroy($id)
    {
        $pop = Pop::findOrFail($id);
        $pop->delete();

        return response()->json([
            'message' => 'Data POP berhasil dihapus!'
        ], 200);
    }
}