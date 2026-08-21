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
        $query = Pop::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_pop', 'like', "%{$search}%")
                    ->orWhere('kode_pop', 'like', "%{$search}%")
                    ->orWhere('kota_kabupaten', 'like', "%{$search}%")
                    ->orWhere('jenis_bangunan', 'like', "%{$search}%");
        }

        $pops = $query->paginate(10)->appends($request->query());

        return view('pop.list-pop', compact('pops'));
    }

    // 2. Tampilkan form Tambah POP
    public function create()
    {
        return view('pop.pop-create');
    }

    // 3. Simpan data POP baru
    public function store(StorePopRequest $request)
    {
        Pop::create($request->validated());

        return redirect()->route('pops.index')
            ->with('success', 'Data POP berhasil ditambahkan!');
    }

    // 4. Menampilkan detail satu POP (digunakan oleh Rectifier dsb.)
    public function show($id)
    {
        $pop = Pop::findOrFail($id);

        return response()->json([
            'message' => 'Detail POP',
            'data'    => $pop
        ], 200);
    }

    // 5. Tampilkan form Edit POP (load data dari DB)
    public function edit($id)
    {
        $pop = Pop::findOrFail($id);
        return view('pop.pop-edit', compact('pop'));
    }

    // 6. Simpan perubahan POP
    public function update(UpdatePopRequest $request, $id)
    {
        $pop = Pop::findOrFail($id);
        $pop->update($request->validated());

        return redirect()->route('pops.index')
            ->with('success', 'Data POP berhasil diperbarui!');
    }

    // 7. Hapus POP
    public function destroy($id)
    {
        $pop = Pop::findOrFail($id);
        $pop->delete();

        return redirect()->route('pops.index')
            ->with('success', 'Data POP berhasil dihapus!');
    }
}