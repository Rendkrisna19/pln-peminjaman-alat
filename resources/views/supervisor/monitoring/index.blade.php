@extends('layouts.app')

@section('title', 'Pemantauan Langsung Aset')

@section('content')
<div x-data="liveMonitoring()" x-init="initData()" class="w-full space-y-6 pb-10">
    
    <!-- ==========================================
         BANNER HEADER (IMAGE BASED)
    =========================================== -->
    <div class="relative w-full rounded-3xl overflow-hidden shadow-sm border border-gray-100 group min-h-[200px] flex items-center bg-gray-900">
        <!-- Background Image Banner -->
        <img src="{{ asset('images/banner1.png') }}" alt="Banner Pemantauan" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-1000 ease-in-out opacity-60">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/80 to-transparent"></div>
        
        <!-- Content Overlay -->
        <div class="relative z-10 p-6 sm:p-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-white mb-2 drop-shadow-lg flex items-center gap-3">
                    <i class="fa-solid fa-satellite-dish text-pln-cyan"></i> Pemantauan Langsung
                </h1>
                <p class="text-gray-300 font-medium text-sm sm:text-base leading-relaxed drop-shadow max-w-xl">
                    Pantau ketersediaan, status penggunaan, dan kondisi fisik setiap alat di lapangan secara waktu nyata.
                </p>
            </div>
            
            <!-- Indikator Waktu Nyata (Live Status) -->
            <div class="shrink-0 inline-flex items-center gap-2.5 px-4 py-2 bg-red-500/20 backdrop-blur-md rounded-xl border border-red-500/30 shadow-lg w-max">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                </span>
                <span class="text-xs font-extrabold text-red-100 tracking-widest uppercase">Koneksi Waktu Nyata Aktif</span>
            </div>
        </div>
    </div>

    <!-- ==========================================
         KOTAK PENCARIAN & FILTER
    =========================================== -->
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        
        <!-- Kolom Pencarian -->
        <div class="flex-1 relative w-full group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-pln-cyan transition-colors"></i>
            </div>
            <input type="text" x-model.debounce.500ms="search" placeholder="Cari Kode Barang atau Nama Alat..." 
                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 bg-gray-50/50 focus:bg-white shadow-inner transition-all">
        </div>

        <!-- Kolom Filter & Tampil Data -->
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            
            <!-- Filter Status Dropdown -->
            <div class="relative w-full sm:w-56 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-filter text-gray-400 group-focus-within:text-pln-cyan transition-colors"></i>
                </div>
                <select x-model="status" class="w-full pl-11 pr-10 py-3.5 rounded-xl border-2 border-gray-100 focus:border-pln-cyan focus:ring-0 text-sm font-bold text-gray-700 appearance-none bg-gray-50/50 focus:bg-white shadow-inner cursor-pointer transition-all">
                    <option value="">Semua Status Tersedia</option>
                    <option value="Tersedia">Barang Tersedia</option>
                    <option value="Dipinjam">Sedang Dipinjam</option>
                    <option value="Diperbaiki">Sedang Diperbaiki</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Limit Jumlah Data -->
            <div class="flex items-center gap-3 border-2 border-gray-100 rounded-xl px-4 py-3 bg-gray-50/50 shadow-inner w-full sm:w-auto justify-between sm:justify-start">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tampil:</span>
                <select x-model="perPage" class="border-0 bg-transparent focus:ring-0 text-sm font-black text-pln-cyan p-0 pr-6 cursor-pointer appearance-none bg-no-repeat" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23008Cca%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-position: right center; background-size: 10px;">
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                    <option value="50">50 Baris</option>
                    <option value="100">100 Baris</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ==========================================
         KONTAINER TABEL (AJAX TARGET)
    =========================================== -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative min-h-[400px]">
        
        <!-- Layar Loading (Muncul saat mengambil data) -->
        <div x-show="loading" style="display: none;" class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center transition-opacity duration-300">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-xl flex items-center justify-center mb-4 border border-gray-100">
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-pln-cyan"></i>
            </div>
            <span class="text-sm font-extrabold text-gray-700 tracking-widest uppercase animate-pulse">Menyinkronkan Data...</span>
        </div>

        <!-- Render Tabel (Konten Parsial) -->
        <div id="monitoring-table-container">
            @include('supervisor.monitoring._table')
        </div>
        
    </div>
</div>

<!-- ==========================================
     SCRIPT ALPINE JS (AJAX HANDLING)
=========================================== -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('liveMonitoring', () => ({
            // Menerima nilai awal dari PHP agar aman dari error string escape
            search: {!! json_encode($search ?? '') !!},
            status: {!! json_encode($filterStatus ?? '') !!},
            perPage: {!! json_encode($perPage ?? 10) !!},
            loading: false,

            initData() {
                // Tonton perubahan input pengguna. Debounce di html menahan request beruntun.
                this.$watch('search', () => this.fetchData(1));
                this.$watch('status', () => this.fetchData(1));
                this.$watch('perPage', () => this.fetchData(1));
                
                // Daftarkan aksi klik pada tombol sortir dan paginasi bawaan Laravel
                this.attachClickListeners();
            },

            fetchData(page = null) {
                this.loading = true;
                
                let url = new URL(window.location.href);
                
                // Perbarui parameter di URL
                url.searchParams.set('search', this.search);
                url.searchParams.set('status_ketersediaan', this.status);
                url.searchParams.set('per_page', this.perPage);
                url.searchParams.set('ajax_request', '1'); // Penanda untuk controller mengembalikan partial view
                
                if(page !== null) {
                    url.searchParams.set('page', page);
                }

                // Jalankan Fetch API (AJAX Murni)
                fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if(!response.ok) throw new Error('Koneksi jaringan bermasalah.');
                    return response.text();
                })
                .then(html => {
                    // Update konten tabel dengan html baru
                    document.getElementById('monitoring-table-container').innerHTML = html;
                    
                    // Modifikasi history URL browser agar bersih dan dapat dibookmark
                    url.searchParams.delete('ajax_request');
                    window.history.pushState({}, '', url.toString());
                    
                    // Daftarkan ulang listener untuk tombol di dalam tabel baru
                    this.attachClickListeners();
                })
                .catch(error => {
                    console.error("Gagal memuat data dari peladen:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },

            attachClickListeners() {
                // Seleksi semua link pada navigasi halaman dan kolom sortir
                const links = document.querySelectorAll('#pagination-links a, .ajax-sort');
                links.forEach(link => {
                    // Menghapus listener ganda dengan teknik cloning
                    let newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                    
                    newLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.loading = true;
                        
                        let url = new URL(newLink.href);
                        url.searchParams.set('ajax_request', '1');

                        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('monitoring-table-container').innerHTML = html;
                            url.searchParams.delete('ajax_request');
                            window.history.pushState({}, '', url.toString());
                            this.attachClickListeners();
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    });
                });
            }
        }));
    });
</script>
@endsection