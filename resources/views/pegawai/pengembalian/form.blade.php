@extends('layouts.app')

@section('title', 'Form Pengembalian Alat')

@section('content')
<!-- Menghapus max-w-4xl mx-auto agar layout fluid menyesuaikan lebar konten utama -->
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Pengembalian Alat</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Tiket Ref: <span class="text-pln-cyan font-bold font-mono px-2 py-0.5 bg-blue-50 rounded border border-blue-100">{{ $peminjaman->kode_peminjaman }}</span></p>
            </div>
        </div>
        <a href="{{ route('pegawai.riwayat.index') }}" class="px-6 py-2.5 bg-white text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition shadow-sm border-2 border-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-sm"></i> Batal / Kembali
        </a>
    </div>

    <!-- Alert Validasi -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm flex items-start gap-3">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mt-0.5"></i>
        <div>
            <h4 class="text-red-800 font-bold mb-1">Gagal menyimpan data!</h4>
            <ul class="text-sm text-red-600 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 p-5 rounded-2xl flex items-start gap-4 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none">
            <i class="fa-solid fa-triangle-exclamation text-9xl -mt-4 mr-4 text-yellow-600"></i>
        </div>
        <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-lg shrink-0 border border-yellow-200">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="z-10">
            <h4 class="font-extrabold text-yellow-800 text-lg">Cek Fisik Alat Sebelum Mengembalikan!</h4>
            <p class="text-sm text-yellow-700 mt-1 font-medium leading-relaxed">
                Pastikan Anda melaporkan kondisi alat sesuai keadaan sebenarnya. Wajib melampirkan minimal 1 foto bukti pengembalian alat (foto alat saat diserahkan atau diletakkan kembali di gudang).
            </p>
        </div>
    </div>

    <!-- Form Container -->
    <form action="{{ route('pegawai.pengembalian.proses', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kiri: List Alat yang dikembalikan -->
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-lg font-bold text-gray-800 ml-1"><i class="fa-solid fa-list-check text-pln-cyan mr-2"></i> Daftar Alat</h2>
                
                @foreach($peminjaman->detail_peminjaman as $detail)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden group hover:border-blue-300 transition-colors">
                        <div class="flex flex-col sm:flex-row">
                            <!-- Detail Alat (Sidebar Card) -->
                            <div class="sm:w-2/5 bg-gray-50/80 p-5 border-b sm:border-b-0 sm:border-r border-gray-200 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-pln-cyan"></div>
                                
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="fa-solid fa-hashtag text-gray-400"></i>
                                    <span class="text-xs font-mono font-bold text-pln-cyan bg-blue-50 px-2 py-1 rounded shadow-sm border border-blue-100">
                                        {{ $detail->item_inventaris->kode_barcode }}
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-gray-800 text-lg leading-tight">{{ $detail->item_inventaris->peralatan->nama_alat }}</h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2" title="{{ $detail->item_inventaris->peralatan->spesifikasi }}">{{ $detail->item_inventaris->peralatan->spesifikasi }}</p>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 border-dashed">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kondisi Awal (Saat Dipinjam)</p>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $detail->kondisi_saat_dipinjam == 'Baik' ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                                        <p class="text-sm font-bold text-gray-700">{{ $detail->kondisi_saat_dipinjam }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Input Kondisi -->
                            <div class="sm:w-3/5 p-5 space-y-4 bg-white">
                                <div>
                                    <label class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-bold text-gray-700">Kondisi Saat Dikembalikan</span>
                                        <span class="text-xs text-red-500 font-bold">*Wajib</span>
                                    </label>
                                    <div class="relative">
                                        <select name="kondisi[{{ $detail->item_inventaris_id }}]" required class="w-full pl-4 pr-10 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-300">
                                            <option value="Baik" {{ $detail->kondisi_saat_dipinjam == 'Baik' ? 'selected' : '' }}>🟢 Baik / Normal</option>
                                            <option value="Rusak Ringan">🟡 Rusak Ringan (Fungsi masih jalan, cacat fisik)</option>
                                            <option value="Rusak Berat">🔴 Rusak Berat (Mati total / Rusak parah)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Kerusakan <span class="text-gray-400 font-normal">(Opsional jika kondisi baik)</span></label>
                                    <textarea name="catatan_kerusakan[{{ $detail->item_inventaris_id }}]" rows="3" placeholder="Ceritakan detail kerusakan yang terjadi selama pemakaian jika ada..." class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-medium resize-none shadow-sm transition hover:border-gray-300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Kanan: Upload Bukti Foto & Tombol Submit -->
            <div class="space-y-6">
                <h2 class="text-lg font-bold text-gray-800 ml-1"><i class="fa-solid fa-camera-retro text-pln-cyan mr-2"></i> Dokumentasi</h2>
                
                <div class="bg-white p-1 rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Area Upload -->
                    <div class="relative w-full h-80 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl hover:bg-gray-100 hover:border-pln-cyan transition-all duration-300 group overflow-hidden flex flex-col justify-center items-center cursor-pointer" id="uploadArea" onclick="document.getElementById('foto_bukti').click()">
                        
                        <!-- Input File Asli (Hidden) -->
                        <input type="file" id="foto_bukti" name="foto_bukti" accept="image/*" required class="hidden" onchange="previewImage(event)">
                        
                        <!-- State 1: Belum Ada Foto -->
                        <div id="uploadPlaceholder" class="flex flex-col items-center justify-center p-6 text-center z-10 transition-opacity duration-300">
                            <div class="w-20 h-20 bg-white shadow flex items-center justify-center rounded-full mb-4 group-hover:scale-110 group-hover:text-pln-cyan transition-transform duration-300">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 group-hover:text-pln-cyan transition-colors"></i>
                            </div>
                            <h3 class="text-base font-extrabold text-gray-800 mb-1 group-hover:text-pln-cyan transition-colors">Unggah Bukti Foto</h3>
                            <p class="text-xs text-gray-500 font-medium">Klik atau drop gambar disini</p>
                            <p class="text-[10px] text-gray-400 mt-4 uppercase tracking-widest font-bold">Max 5MB (JPG/PNG)</p>
                        </div>

                        <!-- State 2: Preview Foto (Muncul jika ada foto) -->
                        <div id="previewContainer" class="absolute inset-0 z-20 hidden bg-black">
                            <img id="imagePreview" class="w-full h-full object-cover opacity-90 group-hover:opacity-60 transition-opacity duration-300" alt="Preview Foto"/>
                            <!-- Overlay Ganti Foto -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white drop-shadow-md">
                                <i class="fa-solid fa-pen-to-square text-3xl mb-2"></i>
                                <span class="font-bold text-sm bg-black/50 px-4 py-2 rounded-full backdrop-blur-sm">Ganti Foto</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full px-6 py-4 bg-pln-cyan text-white font-extrabold text-lg rounded-2xl border-2 border-pln-cyan hover:bg-[#008Cca] hover:shadow-xl transition-all duration-300 shadow-md flex items-center justify-center gap-3 group relative overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                    <i class="fa-solid fa-clipboard-check text-xl relative z-10"></i> 
                    <span class="relative z-10">Konfirmasi Pengembalian</span>
                </button>
            </div>

        </div>
    </form>
</div>

<!-- Script untuk Image Preview -->
<script>
    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];
        const previewContainer = document.getElementById('previewContainer');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');

        if (file) {
            // Validasi ukuran (Opsional, tapi bagus untuk UX)
            if (file.size > 5 * 1024 * 1024) { // 5MB
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                input.value = ''; // Reset input
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                // Tampilkan gambar
                imagePreview.src = e.target.result;
                
                // Ubah visibilitas elemen
                previewContainer.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden', 'opacity-0');
                
                // Hapus styling dashed border agar foto terlihat penuh
                uploadArea.classList.remove('border-dashed', 'border-2', 'p-1');
                uploadArea.classList.add('border-0');
            }
            reader.readAsDataURL(file);
        } else {
            // Jika user cancel dialog (kembalikan ke default)
            previewContainer.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden', 'opacity-0');
            uploadArea.classList.add('border-dashed', 'border-2', 'p-1');
            uploadArea.classList.remove('border-0');
            imagePreview.src = '';
        }
    }
</script>

<style>
    /* Animasi shimmer untuk tombol */
    @keyframes shimmer {
        100% {
            transform: translateX(200%);
        }
    }
</style>
@endsection