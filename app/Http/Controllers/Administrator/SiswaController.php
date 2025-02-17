<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Exports\SiswaTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class SiswaController extends Controller
{
    public function index()
    {
        $data = Siswa::with(['kelas', 'jurusan'])->get();
        $kelas = Kelas::all();
        return view('administrator.siswa.index', compact('data', 'kelas'));
    }

    public function add()
    {
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        return view('administrator.siswa.add', compact('kelas', 'jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn|max:10|regex:/^\d{10}$/',
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'tahun_masuk' => 'required|numeric|min:2000|max:' . date('Y'),
            'status' => 'required|in:Aktif,Tidak Aktif',
            'email' => 'required|email|unique:siswa,email',
            'password' => 'required|min:8',
            'cropped_image' => 'nullable',
        ]);

        $siswa = new Siswa();
        $siswa->fill($request->except('cropped_image', 'password'));
        $siswa->password = bcrypt($request->password);
        $siswa->email_verified_at = now();
        $siswa->status = $request->status;

        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));
            $imageName = 'foto_profil/' . uniqid() . '.jpg';
            $imagePath = storage_path('app/public/' . $imageName);

            if (!is_dir(storage_path('app/public/foto_profil'))) {
                mkdir(storage_path('app/public/foto_profil'), 0775, true);
            }

            if (file_put_contents($imagePath, $image)) {
                $siswa->foto_profil = $imageName;
            } else {
                return redirect()->back()->with('error', 'Gambar gagal diunggah.');
            }
        }

        $siswa->save();
        return redirect()->route('administrator.siswa')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        return view('administrator.siswa.edit', compact('siswa', 'kelas', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nisn' => 'required|unique:siswa,nisn,' . $id . '|max:10|regex:/^\d{10}$/',
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'tahun_masuk' => 'required|numeric|min:2000|max:' . date('Y'),
            'status' => 'required|in:Aktif,Tidak Aktif',
            'email' => 'required|email|unique:siswa,email,' . $id,
            'password' => 'nullable|min:8',
            'cropped_image' => 'nullable',
        ]);

        $siswa->fill($request->except('cropped_image', 'password'));
        if ($request->filled('password')) {
            $siswa->password = bcrypt($request->password);
        }
        $siswa->status = $request->status;

        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));
            $imageName = 'foto_profil/' . uniqid() . '.jpg';
            $imagePath = storage_path('app/public/' . $imageName);

            if (!is_dir(storage_path('app/public/foto_profil'))) {
                mkdir(storage_path('app/public/foto_profil'), 0775, true);
            }

            if (file_put_contents($imagePath, $image)) {
                if ($siswa->foto_profil && file_exists(storage_path('app/public/' . $siswa->foto_profil))) {
                    unlink(storage_path('app/public/' . $siswa->foto_profil));
                }
                $siswa->foto_profil = $imageName;
            } else {
                return redirect()->back()->with('error', 'Gambar gagal diunggah.');
            }
        }

        $siswa->save();
        return redirect()->route('administrator.siswa')->with('success', 'Siswa berhasil diperbarui!');
    }

    public function view($id)
    {
        $siswa = Siswa::with(['kelas', 'jurusan'])->findOrFail($id);
        return view('administrator.siswa.view', compact('siswa'));
    }

    public function delete($id)
    {
        Siswa::findOrFail($id)->delete();
        return redirect()->route('administrator.siswa')->with('success', 'Siswa berhasil dihapus!');
    }

    public function bulkdelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:siswa,id',
        ]);

        try {
            Siswa::whereIn('id', $request->ids)->delete();
            return response()->json(['success' => true, 'message' => 'Data yang dipilih berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function naikkelas(Request $request)
    {
        $studentIds = $request->input('student_ids');
        $kelasId = $request->input('kelas_id');

        if (empty($studentIds) || empty($kelasId)) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa atau kelas tujuan tidak valid.',
            ], 400);
        }

        try {
            foreach ($studentIds as $studentId) {
                $siswa = Siswa::find($studentId);
                if ($siswa) {
                    $siswa->kelas_id = $kelasId;
                    $siswa->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dinaikkan kelas.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menaikkan kelas: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function exportPDF()
    {
        $data = Siswa::with('kelas')->get();
        $tanggal = now()->format('d-m-Y');

        $pdf = Pdf::loadView('exports.print', ['data' => $data]);

        return $pdf->download("data_siswa_esaturasi_{$tanggal}.pdf");
    }

    public function exportExcel()
    {
        $tanggal = now()->format('d-m-Y');

        return Excel::download(new SiswaExport, "data_siswa_esaturasi_{$tanggal}.xlsx");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx|max:2048',
        ]);

        Excel::import(new SiswaImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data siswa berhasil diimpor!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplate, 'template_upload_siswa.xlsx');
    }
}
