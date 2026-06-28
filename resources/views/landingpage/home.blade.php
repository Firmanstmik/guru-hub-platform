@extends('layout.master')
@section('title', 'GuruHub — Mengajar • Berbagi Ilmu • Membangun Masa Depan')
@section('meta_description', 'GuruHub menghubungkan siswa, pengajar, dan institusi dalam satu platform pembelajaran modern. Kursus terkurasi, kelas live, quiz, dan sertifikat terverifikasi.')
@section('meta_image', asset('assets/logo-app/guru_hub_logo.jpeg'))

@php
    $fallbackStats = ['students' => 120000, 'teachers' => 8500, 'courses' => 320, 'certificates' => 520, 'rating' => '4.9'];
    $stats = $fallbackStats;
    $featuredCourses = collect([
        (object) ['title' => 'Fondasi Machine Learning untuk Pendidik', 'category' => (object) ['name' => 'Teknologi'], 'students_count' => 8200, 'cover_image' => null, 'price' => 299000, 'instructor' => 'Dr. Rian Pratama', 'modules' => 12, 'rating' => 4.9, 'progress' => 55, 'badge' => 'Bestseller'],
        (object) ['title' => 'Kalkulus Lanjutan & Aplikasinya', 'category' => (object) ['name' => 'Sains'], 'students_count' => 4600, 'cover_image' => null, 'price' => 0, 'instructor' => 'Prof. Andini', 'modules' => 18, 'rating' => 4.8, 'progress' => 35, 'badge' => null],
        (object) ['title' => 'Strategi Produk untuk Era AI', 'category' => (object) ['name' => 'Bisnis'], 'students_count' => 2100, 'cover_image' => null, 'price' => 449000, 'instructor' => 'Maya Soeharto', 'modules' => 9, 'rating' => 4.9, 'progress' => 25, 'badge' => 'Baru'],
        (object) ['title' => 'Mengajar di Kelas Hybrid yang Modern', 'category' => (object) ['name' => 'Pedagogi'], 'students_count' => 3400, 'cover_image' => null, 'price' => 199000, 'instructor' => 'Dr. Bima Saputra', 'modules' => 14, 'rating' => 4.9, 'progress' => 75, 'badge' => null],
    ]);
    $partners = [
        ['name' => 'STMIK Lombok', 'from' => '#3B82F6', 'to' => '#0E7490'],
        ['name' => 'BMKG', 'from' => '#1E40AF', 'to' => '#22D3EE'],
        ['name' => 'Atlas Transport', 'from' => '#14B8A6', 'to' => '#0E7490'],
        ['name' => 'Sekolah Digital', 'from' => '#3B82F6', 'to' => '#22D3EE'],
        ['name' => 'EduPartner', 'from' => '#0E7490', 'to' => '#14B8A6'],
    ];
    $courseChips = ['Semua', 'Teknologi', 'Sains', 'Bisnis', 'Bahasa', 'Pedagogi'];
    $journeySteps = [
        ['num' => '01', 'label' => 'Discover', 'desc' => 'Telusuri ribuan kursus & pengajar terkurasi.'],
        ['num' => '02', 'label' => 'Enroll', 'desc' => 'Daftar dalam hitungan detik, langsung mulai.'],
        ['num' => '03', 'label' => 'Learn', 'desc' => 'Video, modul interaktif & kelas live.'],
        ['num' => '04', 'label' => 'Practice', 'desc' => 'Proyek nyata dengan feedback ahli.'],
        ['num' => '05', 'label' => 'Certified', 'desc' => 'Sertifikat terverifikasi yang diakui.', 'last' => true],
    ];
    $audienceCards = [
        ['title' => 'Untuk Siswa', 'desc' => 'Kurasi kursus personal, jejak progress, dan sertifikat yang diakui industri.', 'icon' => 'list'],
        ['title' => 'Untuk Pengajar', 'desc' => 'Authoring tools, kelas live, dan analitik dampak — semua dalam satu studio.', 'icon' => 'layers'],
        ['title' => 'Untuk Sekolah', 'desc' => 'Manajemen kelas digital, integrasi rapor, dan komunikasi orang tua.', 'icon' => 'home'],
        ['title' => 'Universitas & Institusi', 'desc' => 'SSO, kepatuhan data, dan dashboard akademik untuk skala ribuan.', 'icon' => 'award'],
    ];
    $testimonials = [
        ['name' => 'Dr. Sinta Wijaya', 'role' => 'Wakil Rektor, Universitas Nusantara', 'quote' => 'GuruHub mengubah cara kampus kami menyelenggarakan kelas hybrid. Analitiknya luar biasa — kami melihat dampak nyata di semester pertama.', 'from' => '#14B8A6', 'to' => '#0E7490'],
        ['name' => 'Rian Pratama', 'role' => 'Pengajar AI · 12k siswa', 'quote' => 'Sebagai pengajar independen, studio GuruHub memberi saya kemewahan tim produksi tanpa biayanya. Pendapatan saya naik 3x.', 'from' => '#0E7490', 'to' => '#0A1A4F'],
        ['name' => 'Maya Soeharto', 'role' => 'Kepala Sekolah, Cakrawala', 'quote' => 'Antarmukanya elegan, performanya cepat, dan tim onboarding luar biasa. Ini terasa seperti produk kelas dunia.', 'from' => '#5EEAD4', 'to' => '#14B8A6'],
    ];

    try {
        $stats['students'] = \App\Models\User::role('siswa')->count() ?: $fallbackStats['students'];
        $stats['teachers'] = \App\Models\User::role('guru')->count() ?: $fallbackStats['teachers'];
        $stats['courses'] = \App\Models\Course::where('status', 'published')->count() ?: $fallbackStats['courses'];
        $stats['certificates'] = \App\Models\Certificate::count() ?: $fallbackStats['certificates'];
        $fromDb = \App\Models\Course::query()->where('status', 'published')->with('category:id,name')->withCount('students')->latest()->take(4)->get();
        if ($fromDb->isNotEmpty()) {
            $featuredCourses = $fromDb->map(function ($c, $i) use ($featuredCourses) {
                $fb = $featuredCourses->get($i % 4);
                $c->instructor = $c->instructor ?? ($fb->instructor ?? 'Pengajar terverifikasi');
                $c->modules = $c->modules ?? ($fb->modules ?? 10);
                $c->rating = $fb->rating ?? 4.9;
                $c->progress = $fb->progress ?? 40;
                $c->badge = $i === 0 ? 'Bestseller' : ($fb->badge ?? null);
                return $c;
            });
            while ($featuredCourses->count() < 4) {
                $featuredCourses->push($featuredCourses->first());
            }
        }
    } catch (\Throwable $e) {}

    $fmtStat = function (int $n): string {
        if ($n >= 100000) return number_format($n / 1000, 0) . 'k+';
        if ($n >= 1000) return number_format($n / 1000, 1) . 'k+';
        return $n . '+';
    };
    $logo = asset('assets/logo-app/guru_hub_logo.jpeg');
@endphp

@section('flush', true)

@section('content')
<div class="gh-landing-page overflow-x-hidden">

    {{-- Hero --}}
    <section class="gh-ref-hero" aria-labelledby="hero-title">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-40 -left-40 h-[600px] w-[600px] rounded-full" style="background:radial-gradient(circle,rgba(59,130,246,0.35),transparent 60%);filter:blur(40px)"></div>
            <div class="absolute top-10 right-0 h-[700px] w-[700px] rounded-full" style="background:radial-gradient(circle,rgba(34,211,238,0.28),transparent 60%);filter:blur(60px)"></div>
            <div class="absolute bottom-0 left-1/4 h-[500px] w-[500px] rounded-full" style="background:radial-gradient(circle,rgba(20,184,166,0.22),transparent 60%);filter:blur(60px)"></div>
        </div>
        <div class="gh-ref-hero-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0 opacity-70" aria-hidden="true">
            @foreach ([['12%','18%'],['62%','10%'],['78%','22%'],['88%','38%'],['48%','8%'],['30%','30%'],['92%','60%']] as $s)
                <span class="gh-ref-star" style="left:{{ $s[0] }};top:{{ $s[1] }}"></span>
            @endforeach
        </div>

        <div class="gh-ref-container-wide relative grid items-center gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5 gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                <div class="gh-ref-glass-badge">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#22D3EE] shadow-[0_0_10px_#22D3EE]"></span>
                    Platform pembelajaran untuk <span class="font-semibold text-[#60A5FA]">Indonesia</span>
                </div>
                <h1 id="hero-title" class="gh-ref-hero-title">
                    Belajar dari para ahli.<br>
                    <span class="gh-ref-hero-grad">Mengajar dengan<br>dampak nyata.</span>
                </h1>
                <p class="gh-ref-hero-lead">
                    GuruHub menghubungkan siswa, pengajar, dan institusi dalam satu pengalaman belajar yang
                    <span class="text-[#60A5FA]">terstruktur</span> — dari <span class="text-[#60A5FA]">penemuan</span> kursus hingga sertifikasi.
                </p>
                <div class="gh-ref-hero-ctas flex flex-wrap items-center gap-3">
                    <a href="{{ url('register/student') }}" class="gh-ref-btn-hero-primary">
                        Mulai belajar sekarang
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="{{ url('register/teacher') }}" class="gh-ref-btn-hero-outline">
                        Bergabung sebagai pengajar
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

                {{-- Mobile app preview (replaces 3D mockup on phone) --}}
                <div class="gh-landing-mobile-preview lg:hidden" aria-hidden="true">
                    <div class="gh-landing-mobile-preview-card">
                        <div class="gh-landing-mobile-preview-ring">
                            <svg viewBox="0 0 36 36" class="h-full w-full -rotate-90" aria-hidden="true">
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="url(#ghMobG1)" stroke-width="3" stroke-dasharray="78 100" stroke-linecap="round"/>
                                <defs><linearGradient id="ghMobG1" x1="0" x2="1"><stop offset="0%" stop-color="#3B82F6"/><stop offset="100%" stop-color="#22D3EE"/></linearGradient></defs>
                            </svg>
                            <div class="absolute inset-0 grid place-items-center text-[0.875rem] font-bold text-white">78%</div>
                        </div>
                        <p class="gh-landing-mobile-preview-label mt-3 text-center">Progress belajar rata-rata</p>
                    </div>
                    <div class="gh-landing-mobile-preview-card">
                        <p class="gh-landing-mobile-preview-value">{{ $fmtStat((int) $stats['students']) }}</p>
                        <p class="gh-landing-mobile-preview-label">Siswa aktif</p>
                    </div>
                    <div class="gh-landing-mobile-preview-card">
                        <p class="gh-landing-mobile-preview-value">{{ number_format((int) $stats['teachers']) }}</p>
                        <p class="gh-landing-mobile-preview-label">Pengajar terverifikasi</p>
                    </div>
                    <div class="gh-landing-mobile-preview-card gh-landing-mobile-preview-card--wide">
                        <span class="gh-landing-mobile-preview-icon">
                            <x-ui.lucide name="award" class="h-6 w-6" />
                        </span>
                        <div class="min-w-0">
                            <p class="gh-landing-mobile-preview-title">Sertifikat terverifikasi</p>
                            <p class="gh-landing-mobile-preview-desc">Dari penemuan kursus hingga sertifikasi — semua dalam satu aplikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dashboard mockup --}}
            <div class="relative hidden lg:col-span-7 lg:block gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                <div class="gh-ref-scene-3d relative flex items-start gap-5 lg:gap-6">
                    <div class="gh-ref-dash-card gh-ref-tilt-main">
                        <div class="flex items-center justify-between px-2 pt-1 pb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="grid h-9 w-9 place-items-center overflow-hidden rounded-full bg-white ring-1 ring-white/20">
                                    <img src="{{ $logo }}" alt="" class="h-9 w-9 scale-[1.35] object-cover">
                                </span>
                                <span class="font-semibold text-white" style="font-family:var(--gh-font-ui)">Dashboard Siswa</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-[130px_1fr] gap-3">
                            <div class="rounded-xl bg-white/[0.025] p-2 text-[12px]" style="font-family:var(--gh-font-ui)">
                                <div class="flex items-center gap-2 rounded-lg bg-white/[0.06] px-3 py-2 text-white ring-1 ring-white/5">
                                    <x-ui.lucide name="home" class="h-3.5 w-3.5 text-[#60A5FA]" /> Beranda
                                </div>
                                <div class="mt-0.5 flex items-center gap-2 px-3 py-2 text-white/50"><x-ui.lucide name="book-open" class="h-3.5 w-3.5" />Kursus Saya</div>
                                <div class="flex items-center gap-2 px-3 py-2 text-white/50"><x-ui.lucide name="video" class="h-3.5 w-3.5" />Live Class</div>
                                <div class="flex items-center gap-2 px-3 py-2 text-white/50"><x-ui.lucide name="clipboard-check" class="h-3.5 w-3.5" />Tugas</div>
                                <div class="flex items-center gap-2 px-3 py-2 text-white/50"><x-ui.lucide name="award" class="h-3.5 w-3.5" />Sertifikat</div>
                            </div>
                            <div class="min-w-0 space-y-3">
                                <div class="px-1">
                                    <p class="text-[22px] font-extrabold text-white" style="font-family:var(--gh-font-ui)">Halo, Andi 👋</p>
                                    <p class="text-[12px] text-white/45">Terus belajar, raih masa depanmu!</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-white/[0.04] p-3.5 ring-1 ring-white/5">
                                        <div class="flex items-center justify-between text-[11px] text-white/55"><span>Progress Belajar</span></div>
                                        <div class="mt-2.5 flex items-center gap-3">
                                            <div class="relative h-[58px] w-[58px] shrink-0">
                                                <svg viewBox="0 0 36 36" class="h-full w-full -rotate-90" aria-hidden="true">
                                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="3"/>
                                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="url(#ghRefG1)" stroke-width="3" stroke-dasharray="78 100" stroke-linecap="round"/>
                                                    <defs><linearGradient id="ghRefG1" x1="0" x2="1"><stop offset="0%" stop-color="#3B82F6"/><stop offset="100%" stop-color="#22D3EE"/></linearGradient></defs>
                                                </svg>
                                                <div class="absolute inset-0 grid place-items-center text-[12px] font-bold text-white">78%</div>
                                            </div>
                                            <div class="text-[11px] leading-tight">
                                                <p class="font-semibold text-white">Hampir selesai!</p>
                                                <p class="text-white/45">Lanjutkan kursusmu.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-white/[0.04] p-3.5 ring-1 ring-white/5">
                                        <p class="text-[11px] text-white/55">Kelas Live Berikutnya</p>
                                        <div class="mt-2 flex gap-2.5">
                                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-gradient-to-br from-[#3B82F6] to-[#22D3EE] text-[10px] font-bold text-white">▶</span>
                                            <div class="text-[11px] leading-tight">
                                                <p class="font-semibold text-white">UI/UX Design System</p>
                                                <p class="text-white/45">Bersama Sarah Wijaya</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-xl bg-white/[0.04] p-3.5 ring-1 ring-white/5">
                                    <p class="text-[11px] font-semibold text-white">Kursus Aktif</p>
                                    <div class="mt-3 space-y-2.5">
                                        @foreach ([['UI/UX Design Fundamental', 82, 'from-orange-500 to-rose-600'], ['Web Development dengan Laravel', 61, 'from-red-700 to-amber-800'], ['Digital Marketing untuk Pemula', 45, 'from-yellow-500 to-orange-600']] as $ac)
                                            <div class="flex items-center gap-3">
                                                <span class="h-8 w-8 shrink-0 rounded-md bg-gradient-to-br {{ $ac[2] }}"></span>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center justify-between text-[11px]"><span class="truncate text-white">{{ $ac[0] }}</span><span class="ml-2 text-white/60">{{ $ac[1] }}%</span></div>
                                                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-white/10"><div class="h-full bg-gradient-to-r from-[#3B82F6] to-[#22D3EE]" style="width:{{ $ac[1] }}%"></div></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden w-[210px] shrink-0 flex-col gap-4 pt-2 lg:flex">
                        <div class="gh-ref-glass-side gh-ref-floaty rounded-2xl p-3.5">
                            <p class="text-[11px] text-white/55">Sertifikat Diperoleh</p>
                            <div class="mt-2 flex items-center gap-2.5">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-[#3B82F6] to-[#22D3EE]">
                                    <x-ui.lucide name="award" class="h-5 w-5 text-white" />
                                </span>
                                <div class="text-[12px] leading-tight">
                                    <p class="font-semibold text-white">UI/UX Design</p>
                                    <p class="text-[10.5px] text-white/45">Diperoleh hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="gh-ref-glass-side gh-ref-floaty gh-ref-floaty-d1 rounded-2xl p-3.5">
                            <p class="text-[11px] text-white/55">Siswa Aktif Minggu Ini</p>
                            <div class="mt-1 flex items-end justify-between gap-2">
                                <svg viewBox="0 0 100 36" class="h-9 w-[90px]" aria-hidden="true">
                                    <defs><linearGradient id="g2" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#22D3EE" stop-opacity=".5"/><stop offset="100%" stop-color="#22D3EE" stop-opacity="0"/></linearGradient></defs>
                                    <path d="M0,28 L12,22 L24,26 L36,16 L48,20 L60,10 L72,14 L84,6 L100,10 L100,36 L0,36 Z" fill="url(#g2)"/>
                                    <polyline fill="none" stroke="#22D3EE" stroke-width="1.6" points="0,28 12,22 24,26 36,16 48,20 60,10 72,14 84,6 100,10"/>
                                </svg>
                                <div class="text-right">
                                    <p class="text-[18px] font-extrabold text-white" style="font-family:var(--gh-font-ui)">+128</p>
                                    <p class="text-[10px] text-[#22D3EE]">12% ↑</p>
                                </div>
                            </div>
                        </div>
                        <div class="gh-ref-glass-side gh-ref-floaty gh-ref-floaty-d2 rounded-2xl p-3.5">
                            <p class="text-[11px] text-white/55">Rating Platform</p>
                            <div class="mt-1 flex items-center justify-between">
                                <div class="flex gap-0.5 text-yellow-400">
                                    @for ($i = 0; $i < 4; $i++) <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg> @endfor
                                    <svg class="h-3.5 w-3.5 text-yellow-400/50" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                                </div>
                                <p class="font-extrabold text-white" style="font-family:var(--gh-font-ui)">4.9/5</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="gh-ref-topo-wrap" aria-hidden="true">
            <div class="absolute inset-0" style="background:radial-gradient(120% 80% at 8% 100%, rgba(20,184,166,0.32), transparent 60%),radial-gradient(120% 80% at 92% 100%, rgba(34,211,238,0.32), transparent 60%);"></div>
            <svg class="absolute inset-x-0 bottom-0 h-[260px] w-full" viewBox="0 0 1440 260" preserveAspectRatio="none">
                <defs>
                    <pattern id="topo" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.1" fill="#67E8F9"/></pattern>
                    <mask id="topoMask"><rect width="1440" height="260" fill="black"/><path d="M0,200 C180,120 360,260 540,180 C720,100 900,240 1080,170 C1260,110 1380,200 1440,160 L1440,260 L0,260 Z" fill="white"/><path d="M0,230 C200,170 400,250 600,210 C820,170 1040,260 1240,210 C1340,190 1400,220 1440,210 L1440,260 L0,260 Z" fill="white" opacity=".7"/></mask>
                </defs>
                <rect width="1440" height="260" fill="url(#topo)" mask="url(#topoMask)" opacity=".9"/>
            </svg>
        </div>
    </section>

    {{-- Partners + stats --}}
    <section class="gh-ref-partners" aria-label="Dipercaya oleh">
        <div class="gh-ref-partners-grid-fine" aria-hidden="true"></div>
        <div class="gh-ref-container relative max-w-[1240px]">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="h-px w-10 bg-[#0A1A4F]/15"></span>
                <span class="gh-ref-eyebrow">Kepercayaan Mitra</span>
                <span class="h-px w-10 bg-[#0A1A4F]/15"></span>
            </div>
            <h2 class="text-center text-[28px] font-extrabold tracking-tight text-[#0A1A4F] sm:text-[34px]" style="font-family:var(--gh-font-ui)">
                Dipercaya oleh institusi & perusahaan terkemuka
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-center text-[15px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">
                Bergabung bersama universitas, sekolah, dan korporasi yang membangun masa depan pendidikan Indonesia.
            </p>
            <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5 gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                @foreach ($partners as $p)
                    <div class="gh-ref-partner-tile">
                        <span class="grid h-10 w-10 place-items-center rounded-xl text-white shadow-[0_6px_18px_-6px_rgba(59,130,246,0.55)]" style="background:linear-gradient(135deg,{{ $p['from'] }},{{ $p['to'] }})">
                            <x-ui.lucide name="shield-check" class="h-5 w-5" />
                        </span>
                        <span class="font-semibold text-[#0A1A4F]" style="font-family:var(--gh-font-ui)">{{ $p['name'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-14 grid grid-cols-2 gap-6 md:grid-cols-4">
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ $fmtStat((int) $stats['students']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Siswa aktif</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ number_format((int) $stats['teachers']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Pengajar terverifikasi</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ $fmtStat((int) $stats['courses']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Institusi mitra</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">4.9 ★</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Rating platform</p></div>
            </div>
        </div>
    </section>

    <div class="gh-ref-light-zone">
        {{-- Courses --}}
        <section id="kursus" class="gh-ref-section relative overflow-hidden">
            <div class="gh-ref-grid-fine-light" aria-hidden="true"></div>
            <div class="gh-ref-container relative">
                <div class="gh-reveal flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <div class="max-w-2xl">
                        <p class="gh-ref-eyebrow">Kursus pilihan</p>
                        <h2 class="gh-ref-display mt-3 text-[34px] leading-[1.05] sm:text-5xl">Pembelajaran yang dirancang <span class="italic text-[#0E7490]">untuk masa depan.</span></h2>
                        <p class="gh-ref-muted mt-4 max-w-xl text-[15px]">Dikurasi bersama pengajar terbaik. Modul interaktif, proyek nyata, dan sertifikat yang diakui industri.</p>
                    </div>
                    <a href="{{ url('register/student') }}" class="hidden items-center gap-2 text-sm font-semibold text-[#0E7490] transition hover:text-[#0A1A4F] sm:inline-flex">
                        Lihat semua kursus
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="gh-ref-chip-row gh-ref-no-scrollbar mt-8 flex gap-2 overflow-x-auto" x-data="{ active: 'Semua' }">
                    @foreach ($courseChips as $chip)
                        <button type="button" class="gh-ref-chip whitespace-nowrap"
                            x-bind:class="active === @js($chip) ? 'gh-ref-chip-active' : ''"
                            @click="active = @js($chip)">{{ $chip }}</button>
                    @endforeach
                </div>

                <div class="gh-ref-course-row mt-8 sm:mt-10 sm:grid sm:grid-cols-2 sm:gap-5 lg:grid-cols-4">
                    @foreach ($featuredCourses->take(4) as $i => $course)
                        <a href="{{ url('register/student') }}" class="gh-ref-l-card block overflow-hidden gh-reveal gh-reveal-delay-{{ min($i + 1, 4) }}"
                            x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                            <div class="gh-ref-thumb gh-ref-thumb-{{ ($i % 4) + 1 }} relative h-44 sm:h-48">
                                @if ($course->cover_image ?? null)
                                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                                @endif
                                <span class="gh-ref-tag absolute top-3 left-3">{{ $course->category->name ?? 'Kursus' }}</span>
                                @if (!empty($course->badge))
                                    <span class="gh-ref-tag-best absolute top-3 right-3">{{ $course->badge }}</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="gh-ref-muted text-[12px]">{{ $course->instructor ?? 'Pengajar' }} · {{ $course->modules ?? 10 }} modul</p>
                                <h3 class="gh-ref-display mt-1.5 text-[19px] leading-snug">{{ $course->title }}</h3>
                                <div class="gh-ref-muted mt-4 flex items-center justify-between text-[12px] font-medium">
                                    <span>★ {{ $course->rating ?? 4.9 }} · {{ number_format($course->students_count ?? 0) }} siswa</span>
                                    <span class="font-semibold text-[#0E7490]">Mulai →</span>
                                </div>
                                <div class="gh-ref-progress mt-3"><i style="width:{{ $course->progress ?? 40 }}%"></i></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Journey --}}
        <section id="perjalanan" class="gh-ref-section border-t gh-ref-divider">
            <div class="gh-ref-container">
                <div class="max-w-2xl gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <p class="gh-ref-eyebrow">Perjalanan belajar</p>
                    <h2 class="gh-ref-display mt-3 text-[34px] leading-[1.05] sm:text-5xl">Dari rasa ingin tahu menuju <span class="italic text-[#0E7490]">sertifikasi.</span></h2>
                    <p class="gh-ref-muted mt-4 text-[15px]">Lima langkah yang dirancang untuk membawa setiap siswa dari pendaftaran hingga pencapaian nyata.</p>
                </div>
                <div class="relative mt-12 sm:mt-14 gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <div class="gh-ref-tl-line" aria-hidden="true"></div>
                    <ol class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-5 sm:gap-8">
                        @foreach ($journeySteps as $step)
                            <li>
                                <div @class(['gh-ref-step-num', 'gh-ref-step-num-last' => !empty($step['last'])])>{{ $step['num'] }}</div>
                                <h3 class="gh-ref-display mt-4 text-[19px]">{{ $step['label'] }}</h3>
                                <p class="gh-ref-muted mt-1 text-[14px]">{{ $step['desc'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </section>

        {{-- For everyone --}}
        <section id="institusi" class="gh-ref-section border-t gh-ref-divider">
            <div class="gh-ref-container grid gap-10 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-5 gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <p class="gh-ref-eyebrow">Untuk semua pemangku pendidikan</p>
                    <h2 class="gh-ref-display mt-3 text-[34px] leading-[1.05] sm:text-5xl">Satu platform. <span class="italic text-[#0E7490]">Siswa, pengajar, dan institusi.</span></h2>
                    <p class="gh-ref-muted mt-4 text-[15px]">Dari kelas tunggal hingga jaringan universitas — GuruHub memberikan infrastruktur belajar modern dengan keamanan tingkat enterprise.</p>
                    <div class="mt-8 grid grid-cols-3 gap-4 sm:gap-6">
                        <div><p class="gh-ref-stat-num text-[28px] font-semibold sm:text-[34px]">{{ $fmtStat((int) $stats['students']) }}</p><p class="gh-ref-muted mt-1 text-[12px]">Siswa aktif</p></div>
                        <div><p class="gh-ref-stat-num text-[28px] font-semibold sm:text-[34px]">{{ number_format((int) $stats['teachers']) }}</p><p class="gh-ref-muted mt-1 text-[12px]">Pengajar</p></div>
                        <div><p class="gh-ref-stat-num text-[28px] font-semibold sm:text-[34px]">{{ $fmtStat((int) $stats['courses']) }}</p><p class="gh-ref-muted mt-1 text-[12px]">Institusi</p></div>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:col-span-7">
                    @foreach ($audienceCards as $i => $card)
                        <div class="gh-ref-l-card p-6 gh-reveal gh-reveal-delay-{{ $i + 1 }}" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                            <div class="gh-ref-icon-bubble"><x-ui.lucide :name="$card['icon']" class="h-5 w-5" /></div>
                            <h3 class="gh-ref-display mt-4 text-[20px]">{{ $card['title'] }}</h3>
                            <p class="gh-ref-muted mt-1.5 text-[14px]">{{ $card['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials --}}
        <section class="gh-ref-section border-t gh-ref-divider">
            <div class="gh-ref-container">
                <div class="max-w-2xl gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <p class="gh-ref-eyebrow">Suara komunitas</p>
                    <h2 class="gh-ref-display mt-3 text-[34px] leading-[1.05] sm:text-5xl">Dipercaya oleh pendidik dan <span class="italic text-[#0E7490]">pemimpin institusi.</span></h2>
                </div>
                <div class="mt-10 grid gap-5 md:grid-cols-3 sm:mt-12">
                    @foreach ($testimonials as $i => $t)
                        <figure class="gh-ref-l-card p-7 gh-reveal gh-reveal-delay-{{ $i + 1 }}" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                            <svg class="gh-ref-quote-mark h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 7h4v4H8c0 3 2 4 3 4v3c-4 0-7-3-7-7V7zm9 0h4v4h-3c0 3 2 4 3 4v3c-4 0-7-3-7-7V7z"/></svg>
                            <blockquote class="mt-4 text-[15px] leading-relaxed text-[#1F2A44]">"{{ $t['quote'] }}"</blockquote>
                            <figcaption class="mt-6 flex items-center gap-3">
                                <span class="h-10 w-10 shrink-0 rounded-full" style="background:linear-gradient(135deg,{{ $t['from'] }},{{ $t['to'] }})"></span>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-semibold">{{ $t['name'] }}</p>
                                    <p class="gh-ref-muted truncate text-[12px]">{{ $t['role'] }}</p>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="gh-ref-section">
            <div class="gh-ref-container max-w-6xl">
                <div class="gh-ref-cta-wrap gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                    <div class="grid items-center gap-8 lg:grid-cols-2">
                        <div>
                            <p class="text-[11px] font-bold tracking-[0.22em] text-[#5EEAD4] uppercase">Mulai hari ini</p>
                            <h2 class="gh-ref-display mt-3 text-[32px] leading-[1.05] text-white sm:text-5xl">
                                Bangun masa depan pendidikan, <span class="italic text-[#5EEAD4]">satu pelajaran setiap hari.</span>
                            </h2>
                            <p class="mt-4 max-w-lg text-[15px] text-white/75">Gratis untuk siswa. Powerful untuk pengajar. Enterprise-ready untuk institusi.</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row lg:justify-end">
                            <a href="{{ url('register/student') }}" class="gh-ref-btn-light-primary">Mulai Belajar Gratis</a>
                            <a href="{{ url('register/teacher') }}" class="gh-ref-btn-light-ghost">Daftar sebagai Pengajar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="border-t gh-ref-divider bg-white py-14">
            <div class="gh-ref-container grid grid-cols-2 gap-8 md:grid-cols-4 md:gap-10">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <span class="grid h-9 w-9 place-items-center overflow-hidden rounded-xl bg-white ring-1 ring-[#0A1A4F]/10">
                            <img src="{{ $logo }}" alt="GuruHub" class="h-9 w-9 scale-[1.35] object-cover">
                        </span>
                        <span class="gh-ref-display text-[20px] font-semibold">GuruHub</span>
                    </div>
                    <p class="gh-ref-muted mt-4 max-w-xs text-[13px]">Mengajar • Berbagi Ilmu • Membangun Masa Depan.</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold tracking-widest text-[#0E7490] uppercase">Produk</p>
                    <ul class="mt-4 space-y-2.5 text-[14px]">
                        <li><a href="{{ url('register/student') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Untuk Siswa</a></li>
                        <li><a href="{{ url('register/teacher') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Untuk Pengajar</a></li>
                        <li><a href="{{ url('register/teacher') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Untuk Institusi</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-[11px] font-semibold tracking-widest text-[#0E7490] uppercase">Perusahaan</p>
                    <ul class="mt-4 space-y-2.5 text-[14px]">
                        <li><a href="{{ url('/') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Tentang</a></li>
                        <li><a href="{{ url('register/teacher') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Karir</a></li>
                        <li><a href="{{ url('/login') }}" class="gh-ref-muted transition hover:text-[#0A1A4F]">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <p class="text-[11px] font-semibold tracking-widest text-[#0E7490] uppercase">Newsletter</p>
                    <form class="mt-4 flex gap-2" action="#" method="get" onsubmit="return false">
                        <input type="email" placeholder="email@anda.com" class="flex-1 rounded-lg border border-[#0A1A4F]/10 bg-white px-3 py-2.5 text-[13px] text-[#0A1A4F] placeholder-[#94A3B8] focus:border-[#0E7490] focus:outline-none">
                        <button type="button" class="gh-ref-btn-light-primary rounded-lg px-4 text-sm">→</button>
                    </form>
                </div>
            </div>
            <div class="gh-ref-container mt-10 flex flex-col gap-2 border-t gh-ref-divider pt-6 text-[12px] sm:flex-row sm:justify-between">
                <span class="gh-ref-muted">© {{ date('Y') }} GuruHub. Semua hak dilindungi.</span>
                <span class="gh-ref-muted">Mengajar • Berbagi Ilmu • Membangun Masa Depan</span>
            </div>
        </footer>
    </div>

    {{-- Mobile sticky dock --}}
    <div class="gh-landing-mobile-dock lg:hidden" role="navigation" aria-label="Aksi cepat">
        <div class="gh-landing-mobile-dock-inner">
            <a href="{{ url('/login') }}" class="gh-landing-dock-secondary">Masuk</a>
            <a href="{{ url('register/student') }}" class="gh-landing-dock-primary">
                Mulai Belajar
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection
