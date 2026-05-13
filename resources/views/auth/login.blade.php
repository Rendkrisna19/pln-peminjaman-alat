@extends('layouts.auth')

@section('content')
<div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
    
    <div class="md:w-1/2 bg-gradient-to-br from-pln-cyan to-[#007BB5] p-10 text-white flex flex-col justify-center items-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-pln-yellow opacity-20 rounded-full translate-x-1/4 translate-y-1/4 blur-3xl"></div>

        <div class="relative z-10 text-center w-full">
            <h2 class="text-3xl font-bold mb-2">E-Tools<span class="text-pln-yellow">Pand.</span></h2>
            <p class="text-sm text-cyan-100 mb-8">Sistem Informasi Manajemen Peminjaman & Monitoring Peralatan Kerja PT PLN (Persero) UP Pandan</p>
            
            <div class="w-64 h-64 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20 p-4 shadow-inner">
                <lottie-player 
                    src="{{ asset('lottie/animasi.json') }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 100%; height: 100%;" 
                    loop 
                    autoplay>
                </lottie-player>
            </div>
        </div>
    </div>

    <div class="md:w-1/2 p-10 sm:p-14 bg-white flex flex-col justify-center">
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800">Selamat Datang 👋</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan masukkan kredensial akun Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Pegawai</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-pln-cyan focus:border-pln-cyan' }} focus:outline-none focus:ring-2 transition-all bg-gray-50 focus:bg-white" 
                        placeholder="contoh@pln.co.id">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 {{ $errors->has('email') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-pln-cyan focus:border-pln-cyan' }} focus:outline-none focus:ring-2 transition-all bg-gray-50 focus:bg-white" 
                        placeholder="••••••••">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 {{ $errors->has('password') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-pln-cyan border-gray-300 rounded focus:ring-pln-cyan transition-colors cursor-pointer">
                    <span class="ml-2 text-sm text-gray-600 group-hover:text-pln-cyan transition-colors">Ingat Saya (7 Hari)</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 mt-6 bg-pln-cyan hover:bg-[#008Cca] text-white font-bold rounded-xl shadow-lg shadow-pln-cyan/30 transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-cyan flex items-center justify-center gap-2">
                <span>Login ke Sistem</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
            
        </form>
    </div>
</div>
@endsection