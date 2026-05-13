@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')

@section('content')
<!-- Memuat library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-full space-y-8 pb-10">
    
    <!-- ==========================================
         BANNER DASHBOARD EKSEKUTIF
    =========================================== -->
    <div class="relative w-full rounded-3xl overflow-hidden shadow-sm border border-gray-100 group min-h-[320px] md:min-h-[400px] flex items-center bg-gray-900">
        <!-- Background Image Banner -->
        <img src="{{ asset('images/banner1.png') }}" alt="Banner Supervisor" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-1000 ease-in-out opacity-80">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/70 to-transparent"></div>
        
        <!-- Content Overlay -->
        <div class="relative z-10 p-6 sm:p-10 w-full md:w-3/4 lg:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/20 w-max mb-4 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse shadow-[0_0_8px_rgba(96,165,250,0.8)]"></span>
                <span class="text-[10px] sm:text-xs font-bold text-white tracking-widest uppercase">Ruang Pantau Supervisor</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-3 drop-shadow-lg leading-tight">
                Dashboard <span class="text-pln-cyan">Eksekutif</span>
            </h1>
            
            <p class="text-gray-300 font-medium text-sm sm:text-base leading-relaxed drop-shadow max-w-2xl mb-8">
                Selamat datang, <span class="font-bold text-white">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</span>. Pantau pergerakan aset, kondisi fisik inventaris, dan tren operasional lapangan secara real-time.
            </p>
        </div>
    </div>

    <!-- ==========================================
         STATISTIK KINERJA (HOVER FULL COLOR)
    =========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Total Inventaris (Hover: BIRU / CYAN PLN) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-pln-cyan hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-default">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-pln-cyan/10 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <i class="fa-solid fa-boxes-stacked text-xl text-pln-cyan group-hover:text-white transition-colors duration-500"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-blue-100 uppercase tracking-wider transition-colors duration-500">Total Fisik Aset</span>
            </div>
            <h3 class="text-4xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $stats['total_alat'] }}</h3>
        </div>

        <!-- Card 2: Sedang Dipinjam (Hover: KUNING PLN) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-[#e5c100] hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-default">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-50 group-hover:bg-white/30 rounded-2xl transition-colors duration-500">
                    <i class="fa-solid fa-person-digging text-xl text-[#e5c100] group-hover:text-white transition-colors duration-500"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-yellow-900 uppercase tracking-wider transition-colors duration-500">Sedang Dipinjam</span>
            </div>
            <h3 class="text-4xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $stats['sedang_dipinjam'] }}</h3>
        </div>

        <!-- Card 3: Kondisi Rusak (Hover: MERAH) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-red-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-default">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-50 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <i class="fa-solid fa-triangle-exclamation text-xl text-red-500 group-hover:text-white transition-colors duration-500"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-red-100 uppercase tracking-wider transition-colors duration-500">Alat Rusak</span>
            </div>
            <h3 class="text-4xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $stats['kondisi_rusak'] }}</h3>
        </div>

        <!-- Card 4: Permintaan Baru (Hover: ABU-ABU / GRAY) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:bg-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 ease-in-out group cursor-default">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-100 group-hover:bg-white/20 rounded-2xl transition-colors duration-500">
                    <i class="fa-solid fa-bell text-xl text-gray-600 group-hover:text-white transition-colors duration-500"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 group-hover:text-gray-300 uppercase tracking-wider transition-colors duration-500">Antrean Izin</span>
            </div>
            <h3 class="text-4xl font-black text-gray-800 group-hover:text-white transition-colors duration-500">{{ $stats['permintaan_baru'] }}</h3>
        </div>

    </div>

    <!-- ==========================================
         AREA CHART (GRAFIK)
    =========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bar Chart: Tren Peminjaman -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-extrabold text-gray-800 text-lg mb-1">Tren Peminjaman Harian</h3>
            <p class="text-xs text-gray-500 font-medium mb-6">Aktivitas pengajuan peminjaman dalam 7 hari terakhir.</p>
            <div class="relative h-72 w-full">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Kondisi Alat -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-extrabold text-gray-800 text-lg mb-1">Kesehatan Inventaris</h3>
            <p class="text-xs text-gray-500 font-medium mb-6">Proporsi kondisi fisik seluruh alat.</p>
            <div class="relative h-60 w-full flex justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
            
            <!-- Custom Legend -->
            <div class="mt-6 flex justify-center gap-4 text-xs font-bold">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-400"></span> Baik</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-yellow-400"></span> Rusak Ringan</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-500"></span> Rusak Berat</div>
            </div>
        </div>

    </div>
</div>

<!-- ==========================================
     SCRIPT CHART.JS
=========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Inisialisasi Bar Chart (Tren Peminjaman)
        const barCtx = document.getElementById('barChart').getContext('2d');
        const trendLabels = @json($chartData['labels']);
        const trendData = @json($chartData['data']);

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Total Peminjaman',
                    data: trendData,
                    backgroundColor: '#008Cca', // PLN Cyan
                    hoverBackgroundColor: '#006a9c',
                    borderRadius: 6, // Ujung bar melengkung
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return context.raw + ' Transaksi'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af', font: {family: 'Inter'} },
                        grid: { color: '#f3f4f6', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#6b7280', font: {family: 'Inter', weight: '600'} },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // 2. Inisialisasi Doughnut Chart (Kondisi Alat)
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        const pieData = @json($pieData);

        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
                datasets: [{
                    data: [pieData['Baik'], pieData['Rusak Ringan'], pieData['Rusak Berat']],
                    backgroundColor: [
                        '#4ade80', // Green 400
                        '#facc15', // Yellow 400
                        '#ef4444'  // Red 500
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%', // Ketebalan cincin doughnut
                plugins: {
                    legend: { display: false }, // Disembunyikan karena sudah buat custom HTML legend
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 10,
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        callbacks: {
                            label: function(context) { return ' ' + context.label + ': ' + context.raw + ' Unit'; }
                        }
                    }
                }
            }
        });

    });
</script>
@endsection