@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="w-full md:max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Akun: <span class="text-pln-cyan">{{ $user->nama_lengkap }}</span></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-2 border-gray-200 p-6 sm:p-8">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required 
                            class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-bold text-gray-800">
                    </div>
                    @error('nama_lengkap') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-user-shield absolute left-4 top-3.5 text-gray-400"></i>
                        <select name="role" required class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-bold text-gray-800 appearance-none">
                            <option value="pegawai" {{ old('role', $user->role) == 'pegawai' ? 'selected' : '' }}>Pegawai (Teknisi Lapangan)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Pengelola Sistem)</option>
                            <option value="supervisor" {{ old('role', $user->role) == 'supervisor' ? 'selected' : '' }}>Supervisor (Pimpinan/Monitoring)</option>
                        </select>
                    </div>
                    @error('role') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                            class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium text-gray-800">
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp/HP</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" 
                            class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-cyan focus:ring-0 transition-colors font-medium text-gray-800">
                    </div>
                    @error('no_telepon') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-yellow-50 p-6 rounded-xl border-2 border-yellow-200">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Password (Opsional)</label>
                <div class="relative">
                    <i class="fa-solid fa-key absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password..." 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:border-pln-yellow focus:ring-0 transition-colors font-medium text-gray-800">
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <hr class="border-2 border-gray-100">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-300 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-pln-yellow text-gray-900 font-bold rounded-xl shadow-md hover:bg-[#e5c100] transition border-2 border-[#e5c100] flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Update Data Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection