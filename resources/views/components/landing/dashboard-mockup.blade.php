@props([
    'dashboard' => [],
    'logo' => '',
])

@php
    $dash = array_merge([
        'student_name' => 'Siswa',
        'avg_progress' => 0,
        'active_courses' => [],
        'next_live' => null,
        'latest_certificate' => null,
        'weekly_students' => 0,
        'weekly_growth' => 0,
        'platform_rating' => '5.0',
    ], $dashboard);

    $progressDash = max(4, min(100, (int) $dash['avg_progress']));
    $ringOffset = round($progressDash * 0.78);

    $navItems = [
        ['icon' => 'home', 'label' => 'Beranda', 'active' => true],
        ['icon' => 'book-open', 'label' => 'Kursus', 'active' => false],
        ['icon' => 'video', 'label' => 'Live', 'active' => false],
        ['icon' => 'clipboard-check', 'label' => 'Tugas', 'active' => false],
        ['icon' => 'award', 'label' => 'Sertifikat', 'active' => false],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'gh-landing-dash-wrap gh-reveal w-full']) }} x-data="ghReveal" x-bind:class="{ 'gh-reveal-visible': visible }">
    <div class="gh-ref-scene-3d relative flex w-full flex-col items-center gap-2 lg:flex-row lg:items-start lg:gap-6">
        <div class="gh-ref-dash-card gh-ref-tilt-main w-full min-w-0 max-w-full flex-1 overflow-hidden">
            <div class="flex items-center justify-between px-1 pb-1.5 pt-0.5 lg:px-2 lg:pb-3 lg:pt-1">
                <div class="flex min-w-0 items-center gap-1.5 lg:gap-2.5">
                    <span class="grid h-6 w-6 shrink-0 place-items-center overflow-hidden rounded-full bg-white ring-1 ring-white/20 lg:h-9 lg:w-9">
                        <img src="{{ $logo }}" alt="" class="h-full w-full scale-[1.35] object-cover">
                    </span>
                    <span class="truncate text-[10px] font-semibold text-white lg:text-[13px]" style="font-family:var(--gh-font-ui)">Dashboard Siswa</span>
                </div>
            </div>

            {{-- Mobile: tab bar ala aplikasi --}}
            <div class="gh-dash-mobile-tabs mb-1.5 flex justify-center gap-0.5 overflow-x-auto px-0 pb-0.5 lg:hidden" aria-hidden="true">
                @foreach ($navItems as $item)
                    <span @class([
                        'inline-flex shrink-0 items-center gap-0.5 rounded-md px-1.5 py-0.5 text-[7px] font-medium',
                        'bg-white/10 text-white ring-1 ring-white/10' => $item['active'],
                        'text-white/45' => !$item['active'],
                    ])>
                        <x-ui.lucide :name="$item['icon']" class="h-2 w-2" />
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-[130px_1fr] lg:gap-3">
                {{-- Desktop sidebar --}}
                <div class="gh-ref-dash-sidebar hidden rounded-xl bg-white/[0.025] p-2 text-[12px] lg:block" style="font-family:var(--gh-font-ui)">
                    @foreach ($navItems as $item)
                        <div @class([
                            'flex items-center gap-2 rounded-lg px-3 py-2',
                            'bg-white/[0.06] text-white ring-1 ring-white/5' => $item['active'],
                            'text-white/50' => !$item['active'],
                        ])>
                            <x-ui.lucide :name="$item['icon']" class="h-3.5 w-3.5" />
                            {{ $item['label'] === 'Kursus' ? 'Kursus Saya' : ($item['label'] === 'Live' ? 'Live Class' : $item['label']) }}
                        </div>
                    @endforeach
                </div>

                <div class="min-w-0 space-y-1.5 lg:space-y-3">
                    <div class="px-0.5">
                        <p class="text-[13px] font-extrabold text-white lg:text-[22px]" style="font-family:var(--gh-font-ui)">Halo, {{ $dash['student_name'] }} 👋</p>
                        <p class="text-[8px] text-white/45 lg:text-[12px]">Terus belajar, raih masa depanmu!</p>
                    </div>

                    <div class="grid grid-cols-2 gap-1.5 lg:gap-3">
                        <div class="rounded-lg bg-white/[0.04] p-1.5 ring-1 ring-white/5 lg:rounded-xl lg:p-3.5">
                            <div class="text-[8px] text-white/55 lg:text-[11px]">Progress Belajar</div>
                            <div class="mt-1 flex items-center gap-1.5 lg:mt-2.5 lg:gap-3">
                                <div class="relative h-8 w-8 shrink-0 lg:h-[58px] lg:w-[58px]">
                                    <svg viewBox="0 0 36 36" class="h-full w-full -rotate-90" aria-hidden="true">
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="3"/>
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="url(#ghDashG1)" stroke-width="3" stroke-dasharray="{{ $ringOffset }} 100" stroke-linecap="round"/>
                                        <defs><linearGradient id="ghDashG1" x1="0" x2="1"><stop offset="0%" stop-color="#3B82F6"/><stop offset="100%" stop-color="#22D3EE"/></linearGradient></defs>
                                    </svg>
                                    <div class="absolute inset-0 grid place-items-center text-[8px] font-bold text-white lg:text-[12px]">{{ $dash['avg_progress'] }}%</div>
                                </div>
                                <div class="min-w-0 text-[8px] leading-tight lg:text-[11px]">
                                    <p class="font-semibold text-white">{{ $dash['avg_progress'] >= 70 ? 'Hampir selesai!' : 'Terus semangat!' }}</p>
                                    <p class="truncate text-white/45">Lanjutkan kursusmu.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-white/[0.04] p-1.5 ring-1 ring-white/5 lg:rounded-xl lg:p-3.5">
                            <p class="text-[8px] text-white/55 lg:text-[11px]">Kelas Live Berikutnya</p>
                            @if ($dash['next_live'])
                                <div class="mt-1 flex gap-1.5 lg:mt-2 lg:gap-2.5">
                                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-md bg-gradient-to-br from-[#3B82F6] to-[#22D3EE] text-[7px] font-bold text-white lg:h-9 lg:w-9 lg:text-[10px]">▶</span>
                                    <div class="min-w-0 text-[8px] leading-tight lg:text-[11px]">
                                        <p class="truncate font-semibold text-white">{{ $dash['next_live']['title'] }}</p>
                                        <p class="truncate text-white/45">Bersama {{ $dash['next_live']['teacher'] }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="mt-1.5 text-[8px] text-white/45 lg:text-[11px]">Belum ada jadwal live.</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-lg bg-white/[0.04] p-1.5 ring-1 ring-white/5 lg:rounded-xl lg:p-3.5">
                        <p class="text-[8px] font-semibold text-white lg:text-[11px]">Kursus Aktif</p>
                        <div class="mt-1.5 space-y-1.5 lg:mt-3 lg:space-y-2.5">
                            @forelse (array_slice($dash['active_courses'], 0, 2) as $ac)
                                <div class="flex items-center gap-1.5 lg:gap-3">
                                    <img src="{{ $ac['cover'] }}" alt="" class="h-5 w-5 shrink-0 rounded object-cover lg:h-8 lg:w-8" loading="lazy">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between text-[8px] lg:text-[11px]">
                                            <span class="truncate text-white">{{ $ac['title'] }}</span>
                                            <span class="ml-1 shrink-0 text-white/60">{{ $ac['progress'] }}%</span>
                                        </div>
                                        <div class="mt-0.5 h-0.5 overflow-hidden rounded-full bg-white/10 lg:h-1.5">
                                            <div class="h-full bg-gradient-to-r from-[#3B82F6] to-[#22D3EE]" style="width:{{ $ac['progress'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[8px] text-white/45 lg:text-[11px]">Belum ada kursus aktif.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Widget cards — swipe horizontal di mobile --}}
        <div class="gh-landing-dash-side-stack w-full lg:w-[210px] lg:shrink-0">
            <p class="mb-1 text-center text-[9px] font-medium text-white/40 lg:hidden">Geser untuk statistik lainnya</p>

            <div class="gh-landing-dash-widgets flex gap-2 lg:flex-col lg:gap-4 lg:pt-2">
                <div class="gh-ref-glass-side gh-ref-floaty min-w-0 rounded-xl p-2 lg:flex-1 lg:rounded-2xl lg:p-3.5">
                    <p class="text-[8px] text-white/55 lg:text-[11px]">Sertifikat Diperoleh</p>
                    @if ($dash['latest_certificate'])
                        <div class="mt-1 flex items-center gap-1.5 lg:mt-2 lg:gap-2.5">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-[#3B82F6] to-[#22D3EE] lg:h-10 lg:w-10">
                                <x-ui.lucide name="award" class="h-3.5 w-3.5 text-white lg:h-5 lg:w-5" />
                            </span>
                            <div class="min-w-0 text-[9px] leading-tight lg:text-[12px]">
                                <p class="truncate font-semibold text-white">{{ $dash['latest_certificate']['title'] }}</p>
                                <p class="truncate text-[7px] text-white/45 lg:text-[10.5px]">{{ $dash['latest_certificate']['label'] }}</p>
                            </div>
                        </div>
                    @else
                        <p class="mt-1.5 text-[8px] text-white/45 lg:text-[11px]">Belum ada sertifikat.</p>
                    @endif
                </div>

                <div class="gh-ref-glass-side gh-ref-floaty gh-ref-floaty-d1 min-w-0 rounded-xl p-2 lg:flex-1 lg:rounded-2xl lg:p-3.5">
                    <p class="text-[8px] text-white/55 lg:text-[11px]">Siswa Baru Minggu Ini</p>
                    <div class="mt-1 flex items-end justify-between gap-1 lg:gap-2">
                        <svg viewBox="0 0 100 36" class="h-6 w-14 lg:h-9 lg:w-[90px]" aria-hidden="true">
                            <defs><linearGradient id="g2dash" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#22D3EE" stop-opacity=".5"/><stop offset="100%" stop-color="#22D3EE" stop-opacity="0"/></linearGradient></defs>
                            <path d="M0,28 L12,22 L24,26 L36,16 L48,20 L60,10 L72,14 L84,6 L100,10 L100,36 L0,36 Z" fill="url(#g2dash)"/>
                            <polyline fill="none" stroke="#22D3EE" stroke-width="1.6" points="0,28 12,22 24,26 36,16 48,20 60,10 72,14 84,6 100,10"/>
                        </svg>
                        <div class="text-right">
                            <p class="text-[12px] font-extrabold text-white lg:text-[18px]" style="font-family:var(--gh-font-ui)">+{{ $dash['weekly_students'] }}</p>
                            <p class="text-[7px] lg:text-[10px] {{ $dash['weekly_growth'] >= 0 ? 'text-[#22D3EE]' : 'text-rose-300' }}">
                                {{ $dash['weekly_growth'] >= 0 ? '+' : '' }}{{ $dash['weekly_growth'] }}% ↑
                            </p>
                        </div>
                    </div>
                </div>

                <div class="gh-ref-glass-side gh-ref-floaty gh-ref-floaty-d2 min-w-0 rounded-xl p-2 lg:flex-1 lg:rounded-2xl lg:p-3.5">
                    <p class="text-[8px] text-white/55 lg:text-[11px]">Rating Platform</p>
                    <div class="mt-1 flex items-center justify-between">
                        <div class="flex gap-0.5 text-yellow-400">
                            @for ($i = 0; $i < 4; $i++)
                                <svg class="h-2.5 w-2.5 lg:h-3.5 lg:w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                            @endfor
                            <svg class="h-2.5 w-2.5 text-yellow-400/50 lg:h-3.5 lg:w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                        </div>
                        <p class="text-[11px] font-extrabold text-white lg:text-base" style="font-family:var(--gh-font-ui)">{{ $dash['platform_rating'] }}/5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
