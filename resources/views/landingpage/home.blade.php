@extends('layout.master')
@section('title', 'GuruHub — Mengajar • Berbagi Ilmu • Membangun Masa Depan')
@section('meta_description', 'GuruHub menghubungkan siswa, pengajar, dan institusi dalam satu platform pembelajaran modern. Kursus terkurasi, kelas live, quiz, dan sertifikat terverifikasi.')
@section('meta_image', asset('assets/logo-app/guru_hub_logo.jpeg'))

@php
    $fmtStat = function (int $n): string {
        if ($n >= 100000) return number_format($n / 1000, 0) . 'k+';
        if ($n >= 1000) return number_format($n / 1000, 1) . 'k+';
        return (string) $n;
    };
    $logo = asset('assets/logo-app/guru_hub_logo.jpeg');
    $courseChips = $categories ?? collect(['Semua']);
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
            </div>

            <x-landing.dashboard-mockup :dashboard="$dashboard" :logo="$logo" class="relative lg:col-span-7" />
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
                @if (count($partners))
                    Bidang pembelajaran unggulan di GuruHub
                @else
                    Platform pembelajaran terpercaya
                @endif
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-center text-[15px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">
                @if (count($partners))
                    Kategori kursus yang dikurasi langsung dari data admin — siap untuk siswa dan pengajar.
                @else
                    Bergabung bersama komunitas belajar yang terus berkembang di Indonesia.
                @endif
            </p>
            @if (count($partners))
            <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5 gh-reveal" x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                @foreach ($partners as $p)
                    <div class="gh-ref-partner-tile">
                        <span class="grid h-10 w-10 place-items-center rounded-xl text-white shadow-[0_6px_18px_-6px_rgba(59,130,246,0.55)]" style="background:linear-gradient(135deg,{{ $p['from'] }},{{ $p['to'] }})">
                            <x-ui.lucide name="book-open" class="h-5 w-5" />
                        </span>
                        <span class="font-semibold text-[#0A1A4F]" style="font-family:var(--gh-font-ui)">{{ $p['name'] }}</span>
                    </div>
                @endforeach
            </div>
            @endif
            <div class="mt-14 grid grid-cols-2 gap-6 md:grid-cols-4">
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ $fmtStat((int) $stats['students']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Siswa terdaftar</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ number_format((int) $stats['teachers']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Pengajar terverifikasi</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ $fmtStat((int) $stats['courses']) }}</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Kursus tersedia</p></div>
                <div class="text-center"><p class="gh-ref-stat-gradient">{{ $stats['rating'] ?? '5.0' }} ★</p><p class="mt-1 text-[12.5px] text-[#0A1A4F]/55" style="font-family:var(--gh-font-ui)">Rating platform</p></div>
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

                <div x-data="{ active: 'Semua' }">
                <div class="gh-ref-chip-row gh-ref-no-scrollbar mt-8 flex gap-2 overflow-x-auto">
                    @foreach ($courseChips as $chip)
                        <button type="button" class="gh-ref-chip whitespace-nowrap"
                            x-bind:class="active === @js($chip) ? 'gh-ref-chip-active' : ''"
                            @click="active = @js($chip)">{{ $chip }}</button>
                    @endforeach
                </div>

                <div class="gh-ref-course-row mt-8 sm:mt-10 sm:grid sm:grid-cols-2 sm:gap-5 lg:grid-cols-4">
                    @forelse ($featuredCourses->take(8) as $i => $course)
                        <a href="{{ url('register/student') }}"
                            x-show="active === 'Semua' || active === @js($course->category_name)"
                            class="gh-ref-l-card block overflow-hidden gh-reveal gh-reveal-delay-{{ min($i + 1, 4) }}"
                            x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
                            <div class="gh-ref-thumb relative h-44 sm:h-48">
                                <img src="{{ $course->cover_url }}" alt="{{ $course->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                                <span class="gh-ref-tag absolute top-3 left-3">{{ $course->category_name }}</span>
                                @if (!empty($course->badge))
                                    <span class="gh-ref-tag-best absolute top-3 right-3">{{ $course->badge }}</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="gh-ref-muted text-[12px]">{{ $course->instructor }} · {{ $course->modules }} modul</p>
                                <h3 class="gh-ref-display mt-1.5 text-[19px] leading-snug">{{ $course->title }}</h3>
                                <div class="gh-ref-muted mt-4 flex items-center justify-between text-[12px] font-medium">
                                    <span>★ {{ $course->rating }} · {{ number_format($course->students_count) }} siswa</span>
                                    <span class="font-semibold text-[#0E7490]">Mulai →</span>
                                </div>
                                <div class="gh-ref-progress mt-3"><i style="width:{{ $course->progress }}%"></i></div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-[#0A1A4F]/15 bg-white/80 p-10 text-center">
                            <p class="gh-ref-muted text-[15px]">Belum ada kursus dipublikasikan. Login sebagai admin untuk menambahkan kursus.</p>
                        </div>
                    @endforelse
                </div>
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
                        <div><p class="gh-ref-stat-num text-[28px] font-semibold sm:text-[34px]">{{ $fmtStat((int) $stats['courses']) }}</p><p class="gh-ref-muted mt-1 text-[12px]">Kursus</p></div>
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
        @if ($testimonials->isNotEmpty())
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
        @endif

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
