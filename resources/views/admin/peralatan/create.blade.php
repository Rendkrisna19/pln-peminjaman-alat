@extends('layouts.app')

@section('title', 'Tambah Peralatan')

@section('content')
<!-- Layout Fluid w-full agar sejajar dengan sidebar -->
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Tambah Katalog Alat</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Daftarkan peralatan baru ke dalam sistem inventaris.</p>
            </div>
        </div>
        <a href="{{ route('admin.peralatan.index') }}" class="px-6 py-2.5 bg-white text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition shadow-sm border-2 border-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
        </a>
    </div>

    <!-- Alert Validasi Bawaan Laravel (Fallback) -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm flex items-start gap-3">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mt-0.5 z-10"></i>
        <div class="z-10">
            <h4 class="text-red-800 font-bold mb-1">Periksa kembali isian Anda!</h4>
            <ul class="text-sm text-red-600 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden group">
        <form action="{{ route('admin.peralatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-3">
                
                <!-- Sisi Kiri: Form Input Data (Lebih Lebar) -->
                <div class="p-6 lg:p-8 xl:col-span-2 space-y-6">
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-file-pen"></i> Detail Informasi Alat
                    </h3>

                    <!-- Nama Alat -->
                    <div class="space-y-2">
                        <label class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Nama Alat Utama</span>
                            <span class="text-xs text-red-500 font-bold">*Wajib</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-screwdriver-wrench absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Contoh: HIDRAULIC CRIMPING TOOLS" 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Total Stok -->
                        <div class="space-y-2">
                            <label class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700">Stok Awal Inventaris</span>
                                <span class="text-xs text-red-500 font-bold">*Wajib</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-boxes-stacked absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="number" name="total_stok" value="{{ old('total_stok', 0) }}" min="0" required 
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300">
                            </div>
                        </div>

                        <!-- Rak Penyimpanan -->
                        <div class="space-y-2">
                            <label class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700">Rak Penyimpanan</span>
                                <span class="text-xs text-gray-400 font-bold">Opsional</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-layer-group absolute left-4 top-3.5 text-gray-400"></i>
                                <select name="rak_id" class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-300">
                                    <option value="">-- Biarkan Kosong Jika Belum Ada --</option>
                                    @foreach($rak as $r)
                                        <option value="{{ $r->id }}" {{ old('rak_id') == $r->id ? 'selected' : '' }}>{{ $r->nama_rak }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spesifikasi -->
                    <div class="space-y-2">
                        <label class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Spesifikasi Detail</span>
                            <span class="text-xs text-gray-400 font-bold">Opsional</span>
                        </label>
                        <div class="relative">
                            <textarea name="spesifikasi" rows="4" placeholder="Contoh: Kapasitas 20 Ton, Stroke 16mm, Material Baja Murni..." 
                                class="w-full px-4 py-4 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-medium text-gray-700 resize-none shadow-sm transition hover:border-gray-300">{{ old('spesifikasi') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Upload Foto Alat -->
                <div class="bg-gray-50/80 p-6 lg:p-8 border-t xl:border-t-0 xl:border-l border-gray-100 flex flex-col h-full relative">
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-200 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-image"></i> Visual Alat
                    </h3>

                    <div class="flex-1 flex flex-col justify-center">
                        <!-- Area Drop Foto Interaktif -->
                        <div class="relative w-full aspect-square max-h-[300px] bg-white border-2 border-dashed border-gray-300 rounded-3xl hover:bg-gray-50 hover:border-pln-cyan transition-all duration-300 overflow-hidden flex flex-col justify-center items-center cursor-pointer shadow-sm mx-auto" id="uploadArea" onclick="document.getElementById('fotoInput').click()">
                            
                            <!-- Hidden Input -->
                            <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewImage(event)">
                            
                            <!-- Placeholder (Sebelum Upload) -->
                            <div id="uploadPlaceholder" class="flex flex-col items-center justify-center p-6 text-center z-10 transition-opacity duration-300">
                                <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-gray-800 mb-1">Unggah Foto Alat</h4>
                                <p class="text-xs text-gray-500 font-medium mb-3">Klik area ini untuk memilih gambar</p>
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-bold tracking-widest uppercase">Max 2MB (JPG/PNG)</span>
                            </div>

                            <!-- Preview (Setelah Upload) -->
                            <div id="previewContainer" class="absolute inset-0 z-20 hidden bg-black">
                                <img id="imagePreview" class="w-full h-full object-cover opacity-90 transition-opacity duration-300" alt="Preview Foto"/>
                                <!-- Overlay Ganti Foto -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 text-white bg-black/40">
                                    <i class="fa-solid fa-pen-to-square text-3xl mb-2"></i>
                                    <span class="font-bold text-sm bg-black/50 px-4 py-2 rounded-full backdrop-blur-sm">Ganti Foto</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Action -->
                    <div class="pt-8 mt-auto flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-pln-cyan text-white font-extrabold text-lg rounded-xl border-2 border-pln-cyan hover:bg-[#008Cca] hover:shadow-xl transition-all duration-300 shadow-md flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                            <i class="fa-solid fa-save relative z-10"></i> 
                            <span class="relative z-10">Simpan Alat</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     SCRIPT UNTUK PREVIEW FOTO & SWEETALERT 
=========================================== -->
<!-- Sertakan Library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Script Preview Gambar
    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];
        const previewContainer = document.getElementById('previewContainer');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');

        if (file) {
            // Validasi Ukuran 2MB
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar!',
                    text: 'Maksimal ukuran foto adalah 2MB.',
                    confirmButtonColor: '#008Cca'
                });
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden', 'opacity-0');
                uploadArea.classList.remove('border-dashed', 'border-2', 'border-gray-300');
                uploadArea.classList.add('border-0');
            }
            reader.readAsDataURL(file);
        }
    }

    // 2. Notifikasi SweetAlert Setelah Submit (Berhasil/Gagal)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#008Cca',
            background: '#ffffff',
            customClass: {
                title: 'text-gray-800 font-bold',
                popup: 'rounded-2xl border border-gray-100 shadow-xl'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
        });
    @endif

   
</script>

<style>
    @keyframes shimmer {
        100% { transform: translateX(200%); }
    }
</style>
@endsection