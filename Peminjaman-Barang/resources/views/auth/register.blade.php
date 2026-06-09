<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - BorrowEase</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 flex items-center justify-center p-4 py-12">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-500 shadow-2xl shadow-indigo-500/30 mb-3">
                <i class="fas fa-boxes-stacked text-white text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Buat Akun BorrowEase</h1>
            <p class="text-indigo-300 text-sm mt-1">Mulai kelola peminjaman inventaris</p>
        </div>

        <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                @if($errors->any())
                <div class="p-4 bg-red-500/20 border border-red-500/30 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap Anda"
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Email <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">No. Telepon</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-white/80 mb-2">Password <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full pl-10 pr-12 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70 transition-colors">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Konfirmasi Password <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-sm"></i>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm mt-2">
                    <i class="fas fa-user-plus mr-2"></i>Buat Akun Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-white/50 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-indigo-300 hover:text-white font-medium transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
