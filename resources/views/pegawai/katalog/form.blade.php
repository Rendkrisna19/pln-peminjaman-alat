@extends('layouts.app')

@section('title', 'Form Peminjaman Alat')

@section('content')
<!-- Menghilangkan pembatas lebar agar layout fluid dan nempel dengan sidebar -->
<div class="w-full space-y-6 pb-10">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-pln-cyan rounded-2xl flex items-center justify-center text-2xl border-2 border-blue-100 shadow-inner">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Formulir Peminjaman</h1>
                <p class="text-sm text-gray-500 font-medium mt-0.5">Lengkapi detail permohonan untuk mengajukan peminjaman alat.</p>
            </div>
        </div>
        <a href="{{ route('pegawai.katalog.index') }}" class="px-6 py-2.5 bg-white text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition shadow-sm border-2 border-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-sm"></i> Batal / Kembali
        </a>
    </div>

    <!-- Alert jika ada error validasi -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm flex items-start gap-3 relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 opacity-5 pointer-events-none">
            <i class="fa-solid fa-circle-exclamation text-9xl -mt-4 mr-4 text-red-800"></i>
        </div>
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mt-0.5 z-10"></i>
        <div class="z-10">
            <h4 class="text-red-800 font-bold mb-1">Terdapat kesalahan pada inputan Anda!</h4>
            <ul class="text-sm text-red-600 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:border-blue-200 transition-colors">
        <form action="{{ route('pegawai.katalog.proses', $alat->id) }}" method="POST">
            @csrf
            
            <!-- Hidden input untuk membawa nilai QTY -->
            <input type="hidden" name="qty" value="{{ $qty }}">

            <div class="grid grid-cols-1 xl:grid-cols-3">
                <!-- Sisi Kiri: Ringkasan Alat (Sidebar Card) -->
                <div class="bg-gray-50/80 p-6 lg:p-8 border-b-2 xl:border-b-0 xl:border-r border-gray-200 relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-pln-cyan hidden xl:block"></div>
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-pln-cyan xl:hidden block"></div>
                    
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-box-open"></i> Alat yang Dipilih
                    </h3>
                    
                    <!-- Frame Foto -->
                    <div class="w-full aspect-video bg-white rounded-xl border border-gray-200 overflow-hidden mb-6 relative shadow-sm group-hover:shadow-md transition-shadow">
                        @if($alat->foto)
                            <img src="{{ asset('storage/' . $alat->foto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image text-5xl mb-2"></i>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="font-extrabold text-gray-800 text-xl leading-tight mb-2">{{ $alat->nama_alat }}</h4>
                    <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6">{{ $alat->spesifikasi }}</p>
                    
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex justify-between items-center shadow-inner">
                        <div class="flex items-center gap-2 text-pln-cyan">
                            <i class="fa-solid fa-cubes text-lg"></i>
                            <span class="text-sm font-extrabold uppercase tracking-wider">Jumlah Pinjam</span>
                        </div>
                        <span class="px-4 py-1.5 bg-white text-pln-cyan border-2 border-pln-cyan text-lg font-extrabold rounded-lg shadow-sm">
                            {{ $qty }} Set
                        </span>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Input -->
                <div class="p-6 lg:p-8 xl:col-span-2 space-y-6">
                    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list"></i> Detail Pekerjaan & Tujuan
                    </h3>

                    <!-- Lokasi Tujuan -->
                    <div class="space-y-2">
                        <label class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Lokasi / Unit Tujuan Pekerjaan</span>
                            <span class="text-xs text-red-500 font-bold">*Wajib</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-building-flag absolute left-4 top-3.5 text-gray-400"></i>
                            <select name="unit_tujuan_id" required class="w-full pl-11 pr-10 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-white shadow-sm cursor-pointer transition hover:border-gray-300">
                                <option value="">-- Pilih Lokasi Unit --</option>
                                @foreach($unit_lokasi as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_tujuan_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }} 
                                        @if($unit->jenis_unit) ({{ $unit->jenis_unit }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Kembali -->
                    <div class="space-y-2">
                        <label class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Estimasi Tanggal Pengembalian</span>
                            <span class="text-xs text-red-500 font-bold">*Wajib</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-calendar-check absolute left-4 top-3.5 text-gray-400"></i>
                            <!-- Menambahkan pl-11 agar text tidak menabrak icon -->
                            <input type="datetime-local" name="estimasi_kembali" required min="{{ date('Y-m-d\TH:i') }}" value="{{ old('estimasi_kembali') }}" 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300 cursor-pointer">
                        </div>
                        <p class="text-[11px] text-gray-400 font-medium italic mt-1">
                            <i class="fa-solid fa-circle-info"></i> Pastikan mengembalikan alat sebelum batas waktu ini untuk menghindari sanksi/denda keterlambatan.
                        </p>
                    </div>

                    <!-- Keterangan -->
                    <div class="space-y-2">
                        <label class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-700">Keterangan / Urgensi Pekerjaan</span>
                            <span class="text-xs text-red-500 font-bold">*Wajib</span>
                        </label>
                        <textarea name="keterangan_pekerjaan" required rows="4" placeholder="Jelaskan secara singkat untuk pekerjaan apa alat ini digunakan, misal: 'Pemeliharaan Trafo Gardu Induk X'..." 
                            class="w-full px-4 py-4 rounded-xl border-2 border-gray-200 focus:border-pln-cyan focus:ring-0 text-sm font-medium text-gray-700 resize-none shadow-sm transition hover:border-gray-300 leading-relaxed">{{ old('keterangan_pekerjaan') }}</textarea>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-4 mt-8">
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-pln-cyan text-white font-extrabold text-lg rounded-xl border-2 border-pln-cyan hover:bg-[#008Cca] hover:shadow-xl transition-all duration-300 shadow-md flex items-center justify-center gap-3 group/btn relative overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn:animate-[shimmer_1.5s_infinite] skew-x-12"></span>
                            <i class="fa-solid fa-paper-plane relative z-10"></i> 
                            <span class="relative z-10">Ajukan Peminjaman</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Animasi shimmer untuk tombol Submit */
    @keyframes shimmer {
        100% { transform: translateX(200%); }
    }
</style>
@endsection