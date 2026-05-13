@php
    // 1. DATA LOGIN (Bagian Atas) - Menampilkan siapa saja yang baru login ke aplikasi
    $loginLogs = \App\Models\LoginLog::with('user')
                    ->orderBy('login_at', 'desc')
                    ->take(3) // Ambil 3 terbaru agar pop-up tidak terlalu panjang
                    ->get();

    // 2. DATA AKTIVITAS ALAT (Bagian Bawah) - Menampilkan log tracking alat
    $trackingLogs = \App\Models\TrackingLog::with(['item_inventaris.peralatan'])
                    ->where('user_id', Auth::id())
                    ->orderBy('tanggal_waktu', 'desc')
                    ->take(3)
                    ->get();
@endphp

<header class="sticky top-0 z-40 flex items-center justify-between px-4 md:px-6 py-3 md:py-4 bg-white/90 backdrop-blur-md border-b-2 border-gray-200 shadow-sm w-full">
    
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="p-2 text-gray-500 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none lg:hidden hover:bg-pln-cyan hover:text-white hover:border-pln-cyan transition-all">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        
        <div class="hidden sm:block">
            <h2 class="text-lg md:text-xl font-extrabold text-gray-800 flex items-center gap-2">
                <span id="greeting-text">Halo</span>, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}!
            </h2>
            <p class="text-xs font-bold text-gray-400 mt-0.5 hidden md:block">
                Semangat bertugas! Jaga K3 dan pastikan peralatan aman.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3 md:gap-5">
        
        <div class="hidden lg:flex flex-col items-end text-right mr-2 bg-gray-50 px-4 py-1.5 rounded-xl border-2 border-gray-100">
            <span id="current-date" class="text-xs font-bold text-pln-cyan uppercase tracking-widest">Memuat...</span>
            <span id="current-time" class="text-sm font-extrabold text-gray-700">--:--:-- WIB</span>
        </div>

        <button onclick="showNotifPopup()" class="relative p-2.5 text-gray-500 bg-gray-50 border-2 border-gray-200 rounded-xl hover:bg-pln-cyan hover:text-white hover:border-pln-cyan transition-all focus:outline-none">
            <i class="fa-solid fa-bell text-lg"></i>
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        </button>

        <div x-data="{ profileOpen: false }" class="relative">
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center focus:outline-none ring-2 ring-transparent hover:ring-pln-cyan rounded-full transition-all">
                
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil" class="w-10 h-10 md:w-11 md:h-11 rounded-full object-cover shadow-md border-2 border-white">
                @else
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-pln-cyan text-white flex items-center justify-center font-extrabold shadow-md border-2 border-white text-lg uppercase">
                        {{ substr(Auth::user()->nama_lengkap, 0, 1) }}
                    </div>
                @endif

            </button>

            <div x-show="profileOpen" style="display: none;" class="absolute right-0 z-50 w-56 mt-3 bg-white rounded-2xl shadow-xl border-2 border-gray-200 overflow-hidden">
                <div class="px-4 py-4 border-b-2 border-gray-100 bg-gray-50 flex items-center gap-3">
                    
                    @if(Auth::user()->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil" class="w-10 h-10 rounded-full object-cover border-2 border-white shrink-0 shadow-sm">
                    @else
                        <div class="w-10 h-10 rounded-full bg-pln-dark text-white flex items-center justify-center font-bold border-2 border-white shrink-0 uppercase shadow-sm">
                            {{ substr(Auth::user()->nama_lengkap, 0, 1) }}
                        </div>
                    @endif

                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->nama_lengkap }}</p>
                        <p class="text-[10px] font-bold text-pln-cyan uppercase tracking-wider">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-bold flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</header>

<script>
    // 1. Script Jam & Ucapan Real-Time
    function updateClock() {
        const now = new Date();
        const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
        document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', optionsDate);
        document.getElementById('current-time').innerText = now.toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';

        const hour = parseInt(now.toLocaleString('en-US', { hour: 'numeric', hour12: false, timeZone: 'Asia/Jakarta' }));
        let greeting = 'Halo';
        if (hour >= 5 && hour < 11) greeting = 'Selamat Pagi 🌅';
        else if (hour >= 11 && hour < 15) greeting = 'Selamat Siang ☀️';
        else if (hour >= 15 && hour < 18) greeting = 'Selamat Sore 🌇';
        else greeting = 'Selamat Malam 🌙';
        
        document.getElementById('greeting-text').innerText = greeting;
    }

    setInterval(updateClock, 1000);
    updateClock();

    // 2. Script Pop-up Notifikasi (Dua Bagian)
    function showNotifPopup() {
        Swal.fire({
            title: '<span class="text-lg font-extrabold text-gray-800">Pusat Notifikasi</span>',
            html: `
                <div class="text-left mt-2 max-h-[65vh] overflow-y-auto pr-2 divide-y-2 divide-gray-100">
                    
                    <div class="pb-4">
                        <h4 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-right-to-bracket text-blue-500"></i> Riwayat Akses Sistem
                        </h4>
                        <div class="space-y-2">
                            @forelse($loginLogs as $log)
                                <div class="p-3 bg-blue-50 border-2 border-blue-100 rounded-xl">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="text-sm font-bold text-gray-800">{{ $log->user->nama_lengkap ?? 'User Dihapus' }}</p>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded">{{ $log->ip_address ?? 'IP Local' }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 font-medium line-clamp-1"><i class="fa-solid fa-laptop text-gray-400 mr-1"></i> {{ $log->user_agent ?? 'Unknown Browser' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-2 font-bold"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->login_at)->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic text-center py-2">Belum ada data login.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-4">
                        <h4 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-pln-yellow"></i> Aktivitas Alat Terakhir
                        </h4>
                        <div class="space-y-2">
                            @forelse($trackingLogs as $track)
                                <div class="p-3 bg-yellow-50 border-2 border-yellow-100 rounded-xl">
                                    <p class="text-sm font-bold text-gray-800 line-clamp-1 mb-2">{{ $track->item_inventaris->peralatan->nama_alat ?? 'Alat Dihapus' }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-pln-cyan uppercase tracking-wider bg-white px-2 py-1 rounded border border-gray-200"><i class="fa-solid fa-tag"></i> {{ $track->status_tracking }}</span>
                                        <span class="text-[10px] text-gray-500 font-bold"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($track->tanggal_waktu)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic text-center py-2">Belum ada aktivitas alat.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            `,
            width: '32em',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-3xl border-2 border-gray-200 shadow-2xl'
            }
        });
    }
</script>