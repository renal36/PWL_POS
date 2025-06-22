<?php

namespace App\Http\Controllers;

use App\Models\BarangModel as Barang; // Menggunakan alias Barang
use App\Models\KategoriModel as Kategori; // Menggunakan alias Kategori
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use Barryvdh\DomPDF\Facade\Pdf; // <<< TAMBAHKAN BARIS INI UNTUK DOMPDF

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object)[
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang';
        $kategori = Kategori::all();

        return view('barang.index', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    public function list(Request $request)
    {
        $barangs = Barang::select('id', 'kategori_id', 'nama_barang', 'harga', 'stok')->with('kategori');

        if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn = '<a href="' . url('/barang/' . $barang->id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->id . '/edit_ajax') . '" class="btn btn-warning btn-sm" onclick="event.preventDefault(); modalAction(this.href, \'Edit Barang\');">Edit</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->id . '/delete_ajax') . '" class="btn btn-danger btn-sm" onclick="event.preventDefault(); modalAction(this.href, \'Hapus Barang\');">Hapus</a>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $kategori = Kategori::all();
        return view('barang.create_ajax', compact('kategori'));
    }

    public function store_ajax(Request $request)
    {
        $validator = validator($request->all(), [
            'kategori_id' => 'required|integer',
            'nama_barang' => 'required|string|max:100',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        Barang::create([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil ditambahkan'
        ]);
    }

    public function edit_ajax($id)
    {
        $barang = Barang::find($id);
        $kategori = Kategori::all();
        return view('barang.edit_ajax', compact('barang', 'kategori'));
    }

    public function update_ajax(Request $request, $id)
    {
        $barang = Barang::find($id);

        $validator = validator($request->all(), [
            'kategori_id' => 'required|integer',
            'nama_barang' => 'required|string|max:100',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $barang->update([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diubah'
        ]);
    }

    public function confirm_ajax($id)
    {
        $barang = Barang::find($id);
        return view('barang.delete_ajax', compact('barang'));
    }

    public function delete_ajax($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil dihapus'
        ]);
    }

    public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = validator($request->all(), [
            'file_barang' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            Excel::import(new \App\Imports\BarangImport, $request->file('file_barang'));
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil diimport'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessage = "Gagal mengimport data:\n";
            foreach ($failures as $failure) {
                $errorMessage .= "Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . "\n";
            }
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengimport: ' . $e->getMessage()
            ]);
        }
    }

    public function export_excel()
    {
        return Excel::download(new BarangExport, 'data_barang_' . date('YmdHis') . '.xlsx');
    }

  
    public function export_pdf()
    {
     
        $barang = Barang::select('kategori_id', 'nama_barang', 'harga', 'stok') // Kolom sesuai dengan yang Anda pakai
            ->orderBy('kategori_id')
            ->orderBy('nama_barang') // Menggunakan nama_barang untuk sorting kedua
            ->with('kategori') // Penting untuk memuat data kategori yang terkait
            ->get();

        
        $pdf = Pdf::loadView('barang.export_pdf', [
            'barang' => $barang 
        ]);

     
        $pdf->setPaper('A4', 'portrait');

       
        $pdf->setOption('isRemoteEnabled', true);

       
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', true);

        $pdf->render();

        return $pdf->stream('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    }
}