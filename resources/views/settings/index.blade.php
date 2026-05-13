@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="w-full space-y-6 pb-10">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 bg-gradient-to-br from-gray-700 to-gray-900 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg transform hover:rotate-6 transition-transform duration-300">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Pengaturan Akun</h1>
                <p class="text-sm text-gray-500 font-medium mt-1">Kelola informasi profil, foto, dan keamanan kata sandi Anda.</p>
            </div>
        </div>
    </div>

    <!-- Alert Validasi Bawaan -->
    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 p-5 rounded-3xl shadow-sm flex items-start gap-3">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mt-0.5 z-10"></i>
        <div class="z-10">
            <h4 class="text-red-800 font-bold mb-1">Pembaruan Gagal!</h4>
            <ul class="text-sm text-red-600 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden group">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-3 divide-y xl:divide-y-0 xl:divide-x divide-gray-100">
                
                <!-- Kolom Kiri: Profil & Foto -->
                <div class="p-6 lg:p-8 xl:col-span-2 space-y-8 bg-gray-50/30">
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-id-badge"></i> Informasi Pribadi
                    </h3>

                    <div class="flex flex-col sm:flex-row gap-8 items-start">
                        <!-- Upload Foto Profil (Avatar) -->
                        <div class="relative shrink-0">
                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-100 relative group cursor-pointer" onclick="document.getElementById('fotoInput').click()">
                                
                                <img id="imagePreview" src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : '' }}" class="w-full h-full object-cover {{ $user->foto_profil ? '' : 'hidden' }}" alt="Foto Profil">
                                
                                <div id="uploadPlaceholder" class="w-full h-full flex flex-col items-center justify-center text-gray-400 {{ $user->foto_profil ? 'hidden' : '' }}">
                                    <i class="fa-solid fa-user text-4xl mb-1"></i>
                                </div>

                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fa-solid fa-camera text-white text-xl mb-1"></i>
                                    <span class="text-[10px] text-white font-bold uppercase tracking-wider">Ubah Foto</span>
                                </div>
                            </div>
                            <input type="file" id="fotoInput" name="foto_profil" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewImage(event)">
                        </div>

                        <!-- Data Text -->
                        <div class="flex-1 w-full space-y-5">
                            <!-- Nama Lengkap -->
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <i class="fa-solid fa-user absolute left-4 top-3.5 text-gray-400"></i>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? $user->nama_lengkap) }}" required 
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Alamat Email</label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                                </div>
                            </div>
                            
                            <!-- Role (Readonly) -->
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Hak Akses (Role)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-shield-halved absolute left-4 top-3.5 text-gray-400"></i>
                                    <input type="text" value="{{ strtoupper($user->role) }}" readonly disabled
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-100 text-sm font-black text-gray-500 shadow-inner cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ubah Password -->
                <div class="p-6 lg:p-8 flex flex-col h-full bg-white">
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> Keamanan Akun
                    </h3>

                    <p class="text-xs text-gray-500 font-medium mb-5 bg-blue-50/50 p-3 rounded-xl border border-blue-100 leading-relaxed">
                        <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Kosongkan kolom kata sandi jika Anda tidak ingin mengubahnya.
                    </p>

                    <div class="space-y-5 flex-1">
                        <!-- Password Baru -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" 
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Ulangi Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-check-double absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi" 
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-8 mt-auto">
                        <button type="submit" class="w-full py-4 bg-pln-cyan text-white font-extrabold text-lg rounded-xl border-2 border-pln-cyan hover:bg-[#008Cca] hover:shadow-xl transition-all duration-300 shadow-md flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                            <i class="fa-solid fa-floppy-disk relative z-10"></i> 
                            <span class="relative z-10">Simpan Pengaturan</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- SweetAlert & Script JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Script Preview Avatar Bulat
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Validasi Ukuran (Max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar!',
                    text: 'Maksimal ukuran foto profil adalah 2MB.',
                    confirmButtonColor: '#008Cca',
                    customClass: { popup: 'rounded-3xl border border-gray-100' }
                });
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Notifikasi SweetAlert Bawaan
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#008Cca',
            customClass: { popup: 'rounded-3xl border border-gray-100 shadow-2xl' }
        });
    @endif
</script>
<style>
    @keyframes shimmer { 100% { transform: translateX(200%); } }
</style>
@endsection