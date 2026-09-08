<?php
namespace App\Http\Controllers;

use App\Models\Pop;
use App\Imports\PopImport;
use Illuminate\Http\Request;
use App\Http\Requests\StorePopRequest;
use App\Http\Requests\UpdatePopRequest;
use Maatwebsite\Excel\Facades\Excel;

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

    // 8. Tampilkan halaman form Import Excel
    public function importForm()
    {
        return view('pop.pop-import');
    }

    // 9. Proses import file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new PopImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['file' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage()]);
        }

        $importedCount = $import->getImportedCount();
        $errorMessages = $import->getErrors();

        $successMsg = "{$importedCount} data POP berhasil diimport." .
                      (count($errorMessages) > 0
                          ? ' ' . count($errorMessages) . ' baris dilewati.'
                          : ' Semua baris berhasil.');

        return redirect()->route('pops.import')
            ->with('import_success', $successMsg)
            ->with('import_errors', $errorMessages);
    }
}