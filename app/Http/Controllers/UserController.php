<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';
        $level = LevelModel::all();

        return view('user.index', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level_nama', function ($user) {
                return $user->level->level_nama ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '
                    <form action="'.url('/user/' . $user->user_id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin menghapus?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list'  => ['Home', 'User', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail user',
        ];

        $activeMenu = 'user';

        return view('user.show', compact('breadcrumb', 'page', 'user', 'activeMenu'));
    }

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit'],
        ];

        $page = (object) [
            'title' => 'Edit user',
        ];

        $activeMenu = 'user';

        return view('user.edit', compact('breadcrumb', 'page', 'user', 'level', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama'     => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        $user = UserModel::find($id);
        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->level_id = $request->level_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait');
        }
    }


    // ================= AJAX Section ==================
public function create_ajax()
{
    return view('user.form_ajax');
}
    public function upload_ajax(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:40960', // max 40MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            return response()->json([
                'status' => true,
                'message' => 'File berhasil diupload',
                'filename' => $filename,
            ]);
        }

        return response()->json(['status' => false, 'message' => 'No file uploaded'], 400);
    }

    // Store new user via AJAX
   public function store_ajax(Request $request)
{
    $validator = Validator::make($request->all(), [
        'level_id' => 'required|exists:m_level,level_id',
        'username' => 'required|string|min:3|max:20|unique:m_user,username',
        'nama'     => 'required|string|min:3|max:100',
        'password' => 'required|string|min:6|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'msgField' => $validator->errors(),
        ]);
    }

    $user = new UserModel();
    $user->level_id = $request->level_id;
    $user->username = $request->username;
    $user->nama = $request->nama;
    $user->password = bcrypt($request->password);
    $user->save();

    return response()->json([
    'status' => true,
    'message' => 'Data berhasil disimpan'
]);

}


    // Form edit user via AJAX (modal)
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', compact('user', 'level'));
    }

    // Update user via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer|exists:m_level,level_id',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama'     => 'required|max:100',
                'password' => 'nullable|min:6|max:20',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $user->level_id = $request->level_id;
            $user->username = $request->username;
            $user->nama = $request->nama;

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate',
            ]);
        }

        return redirect('/');
    }

    // Confirmation modal for delete AJAX
    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', compact('user'));
    }

    // Delete user via AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        }

        return redirect('/');
    }
}
