{{-- Bottom sheet: pilih jenjang setelah tap kategori --}}
<template x-teleport="body">
    <div x-show="sheetOpen" x-cloak class="gh-cat-sheet-root" role="dialog" aria-modal="true"
        :aria-label="current ? 'Pilih jenjang ' + current.name : 'Pilih jenjang'">
        <div class="gh-cat-sheet-backdrop" @click="closeSheet()"></div>

        <div class="gh-cat-sheet-panel" @click.stop>
            <div class="gh-cat-sheet-handle lg:hidden" aria-hidden="true"></div>

            <template x-if="current">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="gh-cat-sheet-icon grid h-12 w-12 shrink-0 place-items-center rounded-2xl text-lg font-bold text-white"
                                :style="'background:linear-gradient(135deg,' + current.from + ',' + current.to + ')'"
                                x-text="current.name.charAt(0)"></span>
                            <div class="min-w-0 text-left">
                                <p class="text-[11px] font-bold tracking-[0.18em] text-[#0E7490] uppercase">Mulai belajar</p>
                                <h3 class="truncate text-[1.125rem] font-bold text-[#0A1A4F]" x-text="current.name"></h3>
                            </div>
                        </div>
                        <button type="button" class="gh-cat-sheet-close" @click="closeSheet()" aria-label="Tutup">
                            <x-ui.lucide name="x" class="h-4 w-4" />
                        </button>
                    </div>

                    <p class="mt-3 text-left text-[0.8125rem] leading-relaxed text-[#0A1A4F]/60" x-text="current.tagline"></p>

                    <p class="mt-5 text-left text-[11px] font-semibold tracking-[0.14em] text-[#0A1A4F]/45 uppercase">Pilih jenjang kamu</p>
                    <div class="mt-2 space-y-2">
                        <template x-for="level in levelsForCategory(current)" :key="level.slug">
                            <button type="button" class="gh-cat-sheet-level" @click="go(level.url)">
                                <span class="gh-cat-sheet-level-badge" x-text="levelBadge(level)"></span>
                                <span class="min-w-0 flex-1 text-left">
                                    <span class="block text-[0.9375rem] font-semibold text-[#0A1A4F]" x-text="level.name"></span>
                                    <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/50"
                                        x-text="level.subjects_count + ' mata pelajaran'"></span>
                                </span>
                                <x-ui.lucide name="arrow-right" class="h-4 w-4 shrink-0 text-[#0E7490]" />
                            </button>
                        </template>
                    </div>

                    <template x-if="!current.levels.length">
                        <p class="mt-3 rounded-xl bg-[#F0F9FF] px-4 py-3 text-left text-[0.8125rem] text-[#0A1A4F]/70">
                            Kursus untuk mapel ini segera hadir. Daftar dulu untuk mendapat kabar terbaru.
                        </p>
                    </template>

                    <div class="mt-4 flex flex-col gap-2">
                        <a href="{{ url('register/student') }}" class="gh-cat-sheet-cta">Mulai belajar gratis</a>
                        <a :href="current.browse_url" class="gh-cat-sheet-link">Lihat detail mapel</a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
