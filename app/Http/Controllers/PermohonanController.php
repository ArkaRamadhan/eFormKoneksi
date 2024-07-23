<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpWord\TemplateProcessor;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Permohonan::latest()->paginate(5);
        return view('dashboard', compact('posts'));
    }

    public function word(Request $request, $id)
    {
        // Ambil data permohonan berdasarkan ID
        $permohonan = Permohonan::findOrFail($id);

        // Ambil data dari model
        $nama = $permohonan->nama;
        $nama_ibu = $permohonan->nama_ibu;
        $cabang = $permohonan->cabang;
        // $jabatan = $permohonan->jabatan;
        $no_telp = $permohonan->no_telp;
        $alasan = $permohonan->alasan;
        // $pendaftaran = $permohonan->pendaftaran;


        $pendaftaran = isset($request->pendaftaran) ? $request->pendaftaran : '';
        $jabatan = isset($request->jabatan) ? $request->jabatan : '';



        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('word.docx');

        $phpWord->setValues([
            'nama'=> $nama,
            'nama_ibu'=> $nama_ibu,
            'cabang'=> $cabang,
            'jabatan'=> $jabatan,
            'no_telp'=> $no_telp,
            'alasan'=> $alasan,
            'pendaftaran'=> $pendaftaran,
        ]);

        // Simpan dokumen sebagai file OOXML
        $filePath = 'Permohonan.docx';
        $phpWord->saveAs($filePath);

        // Kirim file sebagai respons download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('formEmail.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'cabang' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_telp' => 'required|string|min:12',
            'alasan' => 'required|string',
            'pendaftaran' => 'required|string|max:255',
        ]);

        // Buat instance baru dari model yang sesuai
        $data = new Permohonan();
        $data->nama = $request->input('nama');
        $data->nama_ibu = $request->input('nama_ibu');
        $data->cabang = $request->input('cabang');
        $data->jabatan = $request->input('jabatan');
        $data->no_telp = $request->input('no_telp');
        $data->alasan = $request->input('alasan');
        $data->pendaftaran = $request->input('pendaftaran');

        // Simpan data ke dalam database
        $data->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('permohonan.formEmail.success', $data->id);
    }

    public function success($id)
    {
        $data = permohonan::findOrFail($id);
        return view('formEmail/success', compact('data'));
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id)
    {
        //get post by ID
        $post = Permohonan::findOrFail($id);
        //render view with post
        return view('admin.show', compact('post'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        //get post by ID
        $post = Permohonan::findOrFail($id);

        //delete post
        $post->delete();

        //redirect to index
        return redirect('admin/dashboard')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
