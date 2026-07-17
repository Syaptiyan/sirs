<!-- Hero Section -->
<section id="beranda" class="gradient-hero min-h-[90vh] flex items-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
                <div x-show="show" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="badge badge-lg glass mb-4 text-white border-white/30">Rumah Sakit Terpercaya</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Kesehatan Anda,<br>
                        <span class="text-yellow-300">Prioritas Kami</span>
                    </h1>
                    <p class="text-lg sm:text-xl opacity-90 mb-8 max-w-lg">
                        Pelayanan kesehatan modern dengan tenaga medis profesional dan teknologi terkini untuk Anda dan keluarga.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#kontak" class="btn btn-lg glass text-white border-white/30 hover:bg-white/20">
                            Buat Janji
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#layanan" class="btn btn-lg btn-ghost text-white hover:bg-white/10">Lihat Layanan</a>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block" x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)">
                <div x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="glass rounded-3xl p-8 text-center text-white">
                    <svg class="w-32 h-32 mx-auto mb-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p class="text-xl font-semibold">Sistem Informasi<br>Rumah Sakit</p>
                    <p class="opacity-70 mt-2">Terpadu & Modern</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="layanan" class="py-20 bg-base-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="badge badge-primary badge-lg mb-4">Layanan Kami</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Layanan Unggulan</h2>
            <p class="opacity-70 max-w-2xl mx-auto">Kami menyediakan berbagai layanan kesehatan lengkap untuk memenuhi kebutuhan medis Anda dan keluarga.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $i => $svc): ?>
            <div class="card bg-base-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 x-show="visible"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-6"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="transition-delay: <?= $i * 100 ?>ms">
                <div class="card-body items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-4">
                        <?php
                        $icons = [
                            'heart-pulse' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                            'bed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                            'ambulance' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h4m4.5-3.5L17 4m0 0h3a1 1 0 011 1v10a1 1 0 01-1 1h-1M5 20h2m-2 0a2 2 0 100 4 2 2 0 000-4zm12 0a2 2 0 100 4 2 2 0 000-4z"/>',
                            'flask-conical' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>',
                            'scan' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>',
                            'baby' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM12 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                        ];
                        ?>
                        <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><?= $icons[$svc['icon']] ?? $icons['heart-pulse'] ?></svg>
                    </div>
                    <h3 class="card-title"><?= htmlspecialchars($svc['name']) ?></h3>
                    <p class="text-sm opacity-70"><?= htmlspecialchars($svc['desc']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="tentang" class="py-20 bg-base-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div x-data="{ visible: false }" x-intersect.once="visible = true">
                <div x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="glass rounded-3xl p-10 text-center">
                        <svg class="w-48 h-48 mx-auto text-primary opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div x-data="{ visible: false }" x-intersect.once="visible = true">
                <div x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <span class="badge badge-primary badge-lg mb-4">Tentang Kami</span>
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">Rumah Sakit Terpercaya Sejak 2001</h2>
                    <p class="opacity-70 mb-4">
                        SIRS Hospital telah melayani masyarakat selama lebih dari 25 tahun dengan komitmen memberikan pelayanan kesehatan terbaik. Didukung oleh tenaga medis profesional dan fasilitas modern.
                    </p>
                    <p class="opacity-70 mb-6">
                        Kami terus berinovasi menghadirkan sistem informasi terintegrasi untuk memastikan setiap pasien mendapat perawatan yang tepat, cepat, dan akurat.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Akreditasi Paripurna
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dokter Spesialis Lengkap
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Teknologi Medis Terkini
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Doctors Section -->
<section id="dokter" class="py-20 bg-base-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="badge badge-primary badge-lg mb-4">Tim Dokter</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Dokter Spesialis Kami</h2>
            <p class="opacity-70 max-w-2xl mx-auto">Tim dokter spesialis berpengalaman yang siap memberikan perawatan terbaik untuk Anda.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($doctors as $i => $doc): ?>
            <div class="card bg-base-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 x-show="visible"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-6"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="transition-delay: <?= $i * 150 ?>ms">
                <figure class="px-8 pt-8">
                    <div class="w-32 h-32 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg class="w-16 h-16 text-primary opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                </figure>
                <div class="card-body items-center text-center">
                    <h3 class="card-title text-lg"><?= htmlspecialchars($doc['name']) ?></h3>
                    <p class="text-sm text-primary"><?= htmlspecialchars($doc['specialty']) ?></p>
                    <div class="card-actions mt-4">
                        <a href="#kontak" class="btn btn-primary btn-sm btn-outline">Buat Janji</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 gradient-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
            <?php foreach ($stats as $stat): ?>
            <div x-data="{ count: 0, target: <?= $stat['value'] ?>, visible: false }"
                 x-intersect.once="visible = true"
                 x-effect="if (visible) { let step = Math.ceil(target / 60); let interval = setInterval(() => { count += step; if (count >= target) { count = target; clearInterval(interval); } }, 25); }">
                <div class="text-4xl sm:text-5xl font-bold counter-value">
                    <span x-text="count.toLocaleString('id-ID')">0</span><?= htmlspecialchars($stat['suffix']) ?>
                </div>
                <p class="mt-2 opacity-80 text-sm sm:text-base"><?= htmlspecialchars($stat['label']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="kontak" class="py-20 bg-base-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="badge badge-primary badge-lg mb-4">Hubungi Kami</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Kontak & Lokasi</h2>
            <p class="opacity-70 max-w-2xl mx-auto">Jangan ragu untuk menghubungi kami untuk informasi lebih lanjut atau membuat janji temu.</p>
        </div>
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="card bg-base-100 shadow-lg"
                 x-data="{ sending: false, sent: false }">
                <div class="card-body">
                    <h3 class="card-title mb-4">Kirim Pesan</h3>
                    <form @submit.prevent="sending = true; setTimeout(() => { sending = false; sent = true; $el.reset(); setTimeout(() => sent = false, 3000); }, 1500)">
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text">Nama Lengkap</span></label>
                            <input type="text" placeholder="Masukkan nama Anda" class="input input-bordered w-full" required>
                        </div>
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text">Email</span></label>
                            <input type="email" placeholder="email@contoh.com" class="input input-bordered w-full" required>
                        </div>
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text">Nomor Telepon</span></label>
                            <input type="tel" placeholder="08xxxxxxxxxx" class="input input-bordered w-full">
                        </div>
                        <div class="form-control mb-6">
                            <label class="label"><span class="label-text">Pesan</span></label>
                            <textarea placeholder="Tulis pesan Anda..." class="textarea textarea-bordered h-28 w-full" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full" :class="{ 'loading': sending }" :disabled="sending">
                            <span x-show="!sending && !sent">Kirim Pesan</span>
                            <span x-show="sending" x-cloak>Mengirim...</span>
                            <span x-show="sent" x-cloak>Terkirim!</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Alamat</h4>
                                <p class="text-sm opacity-70"><?= htmlspecialchars($contact['address']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Telepon</h4>
                                <p class="text-sm opacity-70"><?= htmlspecialchars($contact['phone']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Email</h4>
                                <p class="text-sm opacity-70"><?= htmlspecialchars($contact['email']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Jam Operasional</h4>
                                <p class="text-sm opacity-70"><?= htmlspecialchars($contact['hours']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
