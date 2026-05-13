<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-80 bg-white text-gray-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.05)] border-r border-gray-200">
    
    <!-- Logo & Header -->
    <div class="flex items-center justify-center h-24 border-b border-gray-100 bg-white shadow-sm shrink-0">
        <div class="flex items-center gap-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/97/Logo_PLN.png" alt="Logo PLN" class="h-12 w-auto drop-shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-blue-800">
                Sistem E-<span class="text-yellow-500">Spark</span>
            </div>
        </div>
    </div>

    <!-- Menu List -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto scrollbar-hide">

        @if(Auth::user()->role === 'admin')
            <!-- UTAMA -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-2">Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <!-- TRANSAKSI -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-6">Transaksi</p>
            
            <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.peminjaman.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.peminjaman.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Verifikasi Peminjaman
            </a>
            
            <a href="{{ route('admin.tracking.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.tracking.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.tracking.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Tracking Log Alat
            </a>

            <!-- MASTER DATA -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-6">Master Data</p>
            
            <a href="{{ route('admin.peralatan.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.peralatan.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.peralatan.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Katalog Peralatan
            </a>
            
            <a href="{{ route('admin.item-inventaris.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.item-inventaris.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.item-inventaris.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Item Fisik (Barcode)
            </a>

            <a href="{{ route('admin.rak-penyimpanan.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.rak-penyimpanan.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.rak-penyimpanan.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Rak Penyimpanan
            </a>

            <a href="{{ route('admin.unit-lokasi.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.unit-lokasi.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.unit-lokasi.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Unit Pemakai (PLTA)
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Kelola Pengguna
            </a>

        @elseif(Auth::user()->role === 'pegawai')
            
            <!-- AREA PEGAWAI -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-2">Area Pegawai</p>
            
            <a href="{{ route('pegawai.dashboard') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('pegawai.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('pegawai.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <!-- OPERASIONAL ALAT -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-6">Operasional Alat</p>

            <a href="{{ route('pegawai.katalog.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('pegawai.katalog.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('pegawai.katalog.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Katalog Alat
            </a>

            <a href="{{ route('pegawai.riwayat.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('pegawai.riwayat.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('pegawai.riwayat.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Peminjaman
            </a>

        @elseif(Auth::user()->role === 'supervisor')
            
            <!-- EKSEKUTIF -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-2">Eksekutif</p>
            
            <a href="{{ route('supervisor.dashboard') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('supervisor.dashboard') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('supervisor.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('supervisor.monitoring.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('supervisor.monitoring.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('supervisor.monitoring.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Live Monitoring
            </a>

            <!-- LAPORAN & CETAK -->
            <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-6">Laporan & Cetak</p>

            <a href="{{ route('supervisor.rekap.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('supervisor.rekap.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('supervisor.rekap.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rekap Peminjaman
            </a>

            <a href="{{ route('supervisor.laporan.aset') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('supervisor.laporan.aset') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('supervisor.laporan.aset') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Kondisi Fisik Aset
            </a>

            <a href="{{ route('supervisor.jejak.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('supervisor.jejak.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <svg class="w-6 h-6 mr-3 {{ request()->routeIs('supervisor.jejak.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m22 4v-4m-3-4h-3M6 13H3m14-9h3M6 4H3M12 21A9 9 0 1112 3a9 9 0 010 18z"></path></svg>
                Jejak Lokasi Alat
            </a>

        @endif

        <!-- PENGATURAN UMUM (Muncul di semua role) -->
        <p class="px-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 mt-6">Preferensi</p>
        <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200 border-l-4 {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-700 border-blue-600 shadow-sm' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
            <svg class="w-6 h-6 mr-3 {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Pengaturan Akun
        </a>

    </nav>
    
    <!-- Bagian Bawah (Profil & Logout) -->
    <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col gap-3">
        <!-- Profil Info -->
        <div class="flex items-center gap-4">
            @if(Auth::user()->foto_profil)
                <!-- Menampilkan foto profil jika ada -->
                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md">
            @else
                <!-- Fallback inisial jika tidak ada foto -->
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pln-cyan to-blue-600 text-white flex items-center justify-center font-bold text-xl shadow-md border-2 border-white">
                    {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->nama_lengkap ?? 'U', 0, 1)) }}
                </div>
            @endif
            
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-gray-800 truncate">{{ Auth::user()->name ?? Auth::user()->nama_lengkap ?? 'User PLN' }}</p>
                <p class="text-sm font-medium text-gray-500 truncate capitalize">{{ Auth::user()->role ?? 'Role' }}</p>
            </div>
        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-base font-bold text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm group">
                <svg class="w-5 h-5 mr-2 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>