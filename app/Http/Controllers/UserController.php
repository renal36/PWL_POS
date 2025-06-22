<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /* =========================================================
     *  HALAMAN LIST USER
     * ========================================================= */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User'],
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem',
        ];

        $activeMenu = 'user';
        $level      = LevelModel::all();

        /* ------ FIX: pakai alias agar sesuai Blade ---------- */
        $kategori = KategoriModel::select(
            'id   as kategori_id',
            'nama_kategori as kategori_nama'
        )->get();
        /* ---------------------------------------------------- */

        return view('user.index', compact(
            'breadcrumb', 'page', 'level', 'activeMenu', 'kategori'
        ));
    }

    /* =========================================================
     *  DATATABLES – JSON LIST USER
     * ========================================================= */
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
                          ->with('level');

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level_nama', fn ($u) => $u->level->level_nama ?? '-')
            ->addColumn('aksi', function ($u) {
                return
                    '<a href="'.url("/user/$u->user_id").'" class="btn btn-info btn-sm">Detail</a> '.
                    '<a href="'.url("/user/$u->user_id/edit").'" class="btn btn-warning btn-sm">Edit</a> '.
                    '<form action="'.url("/user/$u->user_id").'" method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin menghapus?\')">'.
                        csrf_field().method_field('DELETE').
                        '<button type="submit" class="btn btn-danger btn-sm">Hapus</button>'.
                    '</form>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /* =========================================================
     *  FORM CREATE
     * ========================================================= */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah'],
        ];

        $page = (object) ['title' => 'Tambah user baru'];
        $level      = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $r->username,
            'nama'     => $r->nama,
            'password' => bcrypt($r->password),
            'level_id' => $r->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    /* =========================================================
     *  DETAIL / EDIT / UPDATE / DELETE
     * ========================================================= */
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object)['title'=>'Detail User','list'=>['Home','User','Detail']];
        $page       = (object)['title'=>'Detail user'];
        $activeMenu = 'user';

        return view('user.show', compact('breadcrumb', 'page', 'user', 'activeMenu'));
    }

    public function edit(string $id)
    {
        $user  = UserModel::find($id);
        $level = LevelModel::all();
        $breadcrumb = (object)['title'=>'Edit User','list'=>['Home','User','Edit']];
        $page       = (object)['title'=>'Edit user'];
        $activeMenu = 'user';

        return view('user.edit', compact('breadcrumb', 'page', 'user', 'level', 'activeMenu'));
    }

    public function update(Request $r, string $id)
    {
        $r->validate([
            'username' => "required|string|min:3|unique:m_user,username,$id,user_id",
            'nama'     => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        $u = UserModel::find($id);
        if (!$u) return redirect('/user')->with('error','User tidak ditemukan');

        $u->fill([
            'username' => $r->username,
            'nama'     => $r->nama,
            'level_id' => $r->level_id,
        ]);
        if ($r->filled('password')) $u->password = bcrypt($r->password);
        $u->save();

        return redirect('/user')->with('success','Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $u = UserModel::find($id);
        if (!$u) return redirect('/user')->with('error','Data user tidak ditemukan');

        try   { UserModel::destroy($id); }
        catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error','Data user gagal dihapus karena relasi');
        }
        return redirect('/user')->with('success','Data user berhasil dihapus');
    }

    /* =========================================================
     *  ==========  AJAX SECTION  ==============================
     * ========================================================= */
    public function create_ajax()
    {
        return view('user.form_ajax', ['level' => LevelModel::all()]);
    }

    public function store_ajax(Request $r)
    {
        $v = Validator::make($r->all(), [
            'level_id' => 'required|exists:m_level,level_id',
            'username' => 'required|string|min:3|max:20|unique:m_user,username',
            'nama'     => 'required|string|min:3|max:100',
            'password' => 'required|string|min:6|max:20',
        ]);

        if ($v->fails()) return response()->json(['status'=>false,'msgField'=>$v->errors()]);

        UserModel::create([
            'level_id' => $r->level_id,
            'username' => $r->username,
            'nama'     => $r->nama,
            'password' => bcrypt($r->password),
        ]);

        return response()->json(['status'=>true,'message'=>'Data berhasil disimpan']);
    }

    public function edit_ajax(string $id)
    {
        return view('user.edit_ajax', [
            'user'  => UserModel::find($id),
            'level' => LevelModel::select('level_id','level_nama')->get()
        ]);
    }

    public function update_ajax(Request $r, $id)
    {
        if (!($r->ajax() || $r->wantsJson())) return redirect('/');
        $v = Validator::make($r->all(), [
            'level_id' => 'required|exists:m_level,level_id',
            'username' => "required|max:20|unique:m_user,username,$id,user_id",
            'nama'     => 'required|max:100',
            'password' => 'nullable|min:6|max:20',
        ]);
        if ($v->fails()) return response()->json(['status'=>false,'msgField'=>$v->errors()]);

        $u = UserModel::find($id);
        if (!$u) return response()->json(['status'=>false,'message'=>'Data tidak ditemukan']);

        $u->fill([
            'level_id'=>$r->level_id,
            'username'=>$r->username,
            'nama'=>$r->nama,
        ]);
        if ($r->filled('password')) $u->password = bcrypt($r->password);
        $u->save();

        return response()->json(['status'=>true,'message'=>'Data berhasil diupdate']);
    }

    public function confirm_ajax(string $id)
    {
        return view('user.confirm_ajax', ['user'=>UserModel::find($id)]);
    }

    public function delete_ajax(Request $r, $id)
    {
        if (!($r->ajax() || $r->wantsJson())) return redirect('/');
        $u = UserModel::find($id);
        if (!$u) return response()->json(['status'=>false,'message'=>'Data tidak ditemukan']);
        $u->delete();
        return response()->json(['status'=>true,'message'=>'Data berhasil dihapus']);
    }

    public function upload_ajax(Request $r)
    {
        $r->validate(['file'=>'required|file|max:40960']);
        if (!$r->hasFile('file'))
            return response()->json(['status'=>false,'message'=>'No file uploaded'],400);

        $file = $r->file('file');
        $name = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $name);

        return response()->json(['status'=>true,'filename'=>$name,'message'=>'File berhasil diupload']);
    }
}
