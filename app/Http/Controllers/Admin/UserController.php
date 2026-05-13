<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // [TAMBAHAN WAJIB] Untuk Auth::id()

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort_field', 'nama_lengkap');
        $sortDirection = $request->input('sort_direction', 'asc');
        $filterRole = $request->input('role');

        $query = User::query();

        if ($search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($filterRole) {
            $query->where('role', $filterRole);
        }

        $users = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'sortField', 'sortDirection', 'filterRole'));
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,pegawai,supervisor',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // [PERBAIKAN] Menggunakan $id manual
    public function edit($id) 
    { 
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user')); 
    }

    // [PERBAIKAN] Menggunakan $id manual
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_users,email,' . $user->id,
            'role' => 'required|in:admin,pegawai,supervisor',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $data = $request->only(['nama_lengkap', 'email', 'role', 'no_telepon']);
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // [PERBAIKAN] Menggunakan $id manual
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}