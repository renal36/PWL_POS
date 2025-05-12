<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = UserModel::all(); 
        return view('user', ['data' => $user]); 
    }

    public function tambah()
    {
        return view('user_tambah'); 
    }

    public function tambah_simpan(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required',
            'level_id' => 'required|numeric',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'User berhasil ditambahkan');
    }

   public function ubah($id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        return view('user_ubah', ['data' => $user]); 
    }

    // Menyimpan perubahan data user
    public function ubah_simpan($id, Request $request)
    {
        // Debugging request
        dd($request->all());

        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'level_id' => 'required|integer'
        ]);

        // Mencari user berdasarkan ID
        $user = UserModel::find($id);
        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        // Proses update data user
        $user->username = $request->username;
        $user->nama = $request->nama;

        // Update password jika ada perubahan
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->level_id = $request->level_id;

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman user dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }





         public function hapus($id)
    {
        $user = UserModel::find($id); 
        $user -> delete();

        return view('/user'); 
    }

    
}
