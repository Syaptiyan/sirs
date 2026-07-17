<!DOCTYPE html>
<html lang="id" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'SIRS - Rumah Sakit') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass {
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 50%, #a855f7 100%);
        }
        .counter-value { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="bg-base-100 text-base-content min-h-screen flex flex-col transition-colors duration-300">

    <!-- Navbar -->
    <nav class="glass fixed top-0 left-0 right-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="text-xl font-bold">SIRS</span>
                </a>

                <div class="hidden md:flex items-center gap-6">
                    <a href="#beranda" class="hover:text-primary transition">Beranda</a>
                    <a href="#layanan" class="hover:text-primary transition">Layanan</a>
                    <a href="#tentang" class="hover:text-primary transition">Tentang</a>
                    <a href="#dokter" class="hover:text-primary transition">Dokter</a>
                    <a href="#kontak" class="hover:text-primary transition">Kontak</a>
                    <button @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light')" class="btn btn-ghost btn-circle btn-sm">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                </div>

                <div class="md:hidden flex items-center gap-2">
                    <button @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light')" class="btn btn-ghost btn-circle btn-sm">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <button @click="open = !open" class="btn btn-ghost btn-circle btn-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden glass px-4 pb-4 space-y-2">
            <a href="#beranda" @click="open=false" class="block py-2 hover:text-primary">Beranda</a>
            <a href="#layanan" @click="open=false" class="block py-2 hover:text-primary">Layanan</a>
            <a href="#tentang" @click="open=false" class="block py-2 hover:text-primary">Tentang</a>
            <a href="#dokter" @click="open=false" class="block py-2 hover:text-primary">Dokter</a>
            <a href="#kontak" @click="open=false" class="block py-2 hover:text-primary">Kontak</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 pt-16">
        <?php include __DIR__ . '/../landing/index.php'; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-base-200 text-base-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="text-xl font-bold">SIRS</span>
                    </div>
                    <p class="text-sm opacity-70">Sistem Informasi Rumah Sakit terpadu untuk pelayanan kesehatan modern dan berkualitas.</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm opacity-70">
                        <li><a href="#layanan" class="hover:text-primary">Layanan</a></li>
                        <li><a href="#dokter" class="hover:text-primary">Dokter</a></li>
                        <li><a href="#kontak" class="hover:text-primary">Hubungi Kami</a></li>
                        <li><a href="/login" class="hover:text-primary">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-sm opacity-70">
                        <li><?= htmlspecialchars($contact['address']) ?></li>
                        <li>Telp: <?= htmlspecialchars($contact['phone']) ?></li>
                        <li>Email: <?= htmlspecialchars($contact['email']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-base-300 mt-8 pt-6 text-center text-sm opacity-60">
                &copy; <?= date('Y') ?> SIRS. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
