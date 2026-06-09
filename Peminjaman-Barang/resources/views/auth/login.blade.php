<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BorrowEase</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 flex items-center justify-center p-4">
    <!-- Background blobs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-500 shadow-2xl shadow-indigo-500/30 mb-4">
                <i class="fas fa-boxes-stacked text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">BorrowEase</h1>
            <p class="text-indigo-300 text-sm mt-1">Sistem Peminjaman Barang Inventaris</p>
        </div>

        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
            <h2 class="text-xl font-bold text-white mb-6">Selamat Datang Kembali 👋</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                @if($errors->any())
                <div class="p-4 bg-red-500/20 border border-red-500/30 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-300"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Alamat Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nama@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-white/80 mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-12 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70 transition-colors">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/10 text-indigo-500 focus:ring-indigo-400">
                        <span class="text-sm text-white/70">Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" id="login-btn"
                        class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Dashboard
                </button>
            </form>

            <p class="text-center text-sm text-white/50 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-indigo-300 hover:text-white font-medium transition-colors">Daftar Sekarang</a>
            </p>
        </div>

        <!-- Demo account info -->
        <div class="mt-4 p-4 bg-white/5 border border-white/10 rounded-2xl text-center">
            <p class="text-xs text-white/40 mb-2">Demo Account</p>
            <p class="text-xs text-white/60"><span class="font-mono">admin@borrowease.com</span> / <span class="font-mono">password</span></p>
        </div>
    </div>
</body>
</html>
