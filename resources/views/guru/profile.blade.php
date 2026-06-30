@extends('layout.master-app')

@php
    use App\Support\TeacherProfileChecklist;

    $hasProfile = (bool) $profile->id;
    $verificationStatus = $profile->verification_status ?? 'pending';
    $verificationLabel = TeacherProfileChecklist::verificationLabel($verificationStatus);
    $verificationVariant = TeacherProfileChecklist::verificationVariant($verificationStatus);
@endphp

@section('content')
    <div class="gh-app-page" x-data="{ profileTab: 'ringkasan' }">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner space-y-4">

            @unless ($hasProfile)
                <div class="gh-profile-onboard">
                    <p class="text-sm font-bold text-amber-900">Lengkapi profil pengajar Anda</p>
                    <p class="mt-1 text-xs text-amber-800/90">Profil wajib diisi agar bisa membuat kelas, tampil di katalog, dan menerima pencairan pendapatan.</p>
                    <button type="button" onclick="openModal('addProfileModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-3">
                        Mulai isi profil
                    </button>
                </div>
            @endunless

            {{-- Hero --}}
            <section class="gh-profile-hero">
                <div class="gh-profile-hero-glow" aria-hidden="true"></div>
                <div class="gh-profile-hero-body">
                    <div class="gh-profile-hero-main">
                        <div class="relative shrink-0">
                            <x-app.user-avatar :user="$user" size="2xl" />
                            @if ($hasProfile)
                                <button type="button" onclick="openModal('uploadMediaModal')"
                                    class="gh-app-btn gh-app-btn-primary absolute -bottom-1 -right-1 !min-h-0 !h-8 !w-8 !rounded-xl !p-0 shadow-lg border-2 border-white"
                                    title="Ubah foto">
                                    <x-ui.lucide name="upload" class="h-3.5 w-3.5" />
                                </button>
                            @endif
                        </div>
                        <div class="gh-profile-hero-meta">
                            <p class="gh-app-eyebrow">Ruang Pengajar</p>
                            <h1 class="gh-profile-hero-name">{{ $user->name }}</h1>
                            <p class="gh-profile-hero-headline">{{ $profile->title ?? 'Guru Pengajar GuruHub' }}</p>
                            <div class="gh-profile-hero-badges">
                                <x-app.badge :variant="$verificationVariant">{{ $verificationLabel }}</x-app.badge>
                                <x-app.badge variant="neutral">⭐ {{ number_format($profile->average_rating ?? 0, 1) }}</x-app.badge>
                                @if ($publishedCoursesCount > 0)
                                    <x-app.badge variant="info">{{ $publishedCoursesCount }} kelas aktif</x-app.badge>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="gh-profile-progress">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] font-bold text-[#475569]">Kelengkapan profil</span>
                            <span class="text-sm font-black text-[#06122E]">{{ $checklist['percent'] }}%</span>
                        </div>
                        <div class="gh-profile-progress-bar" role="progressbar" aria-valuenow="{{ $checklist['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                            <span style="width: {{ $checklist['percent'] }}%"></span>
                        </div>
                        <p class="text-[10px] text-[#94A3B8]">{{ $checklist['done'] }}/{{ $checklist['total'] }} langkah selesai</p>
                    </div>
                </div>
            </section>

            {{-- Quick actions --}}
            <div class="gh-profile-quick-grid">
                @if ($hasProfile)
                    <button type="button" onclick="openModal('editProfileModal')" class="gh-profile-quick-btn">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="edit" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Edit profil</span>
                    </button>
                    <button type="button" onclick="openModal('uploadMediaModal')" class="gh-profile-quick-btn">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="upload" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Foto & CV</span>
                    </button>
                    <a href="/courses" class="gh-profile-quick-btn">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="layers" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Kelola kelas</span>
                    </a>
                    <a href="/earnings" class="gh-profile-quick-btn">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="circle-dollar-sign" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Pendapatan</span>
                    </a>
                @else
                    <button type="button" onclick="openModal('addProfileModal')" class="gh-profile-quick-btn sm:col-span-2">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="user" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Buat profil</span>
                    </button>
                    <a href="/guru-dashboard" class="gh-profile-quick-btn">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="layout-dashboard" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Dashboard</span>
                    </a>
                    <a href="/courses" class="gh-profile-quick-btn opacity-60 pointer-events-none" title="Lengkapi profil dulu">
                        <span class="gh-profile-quick-icon"><x-ui.lucide name="layers" class="h-4 w-4" /></span>
                        <span class="gh-profile-quick-label">Kelas</span>
                    </a>
                @endif
            </div>

            <div class="gh-profile-layout">
                {{-- Sidebar: checklist + mini stats --}}
                <aside class="gh-profile-sidebar space-y-4">
                    @unless ($checklist['is_complete'])
                        <div class="gh-app-card">
                            <div class="mb-3">
                                <p class="gh-app-eyebrow">Checklist</p>
                                <h2 class="gh-app-heading mt-0.5">Yang perlu dilengkapi</h2>
                            </div>
                            <div class="gh-profile-checklist">
                                @foreach ($checklist['items'] as $item)
                                    @if (! $item['done'])
                                        <button type="button" onclick="openModal('{{ $item['modal'] }}')"
                                            class="gh-profile-check-item">
                                            <span class="gh-profile-check-icon gh-profile-check-icon--todo">
                                                <x-ui.lucide :name="$item['icon']" class="h-4 w-4" />
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block text-[12px] font-bold text-[#0A1A4F]">{{ $item['label'] }}</span>
                                                <span class="block text-[10px] text-[#94A3B8]">{{ $item['hint'] }}</span>
                                            </span>
                                            <x-ui.lucide name="arrow-right" class="h-4 w-4 shrink-0 text-[#94A3B8]" />
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="gh-app-card border-emerald-200/60 bg-emerald-50/40">
                            <div class="flex items-start gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-600">
                                    <x-ui.lucide name="badge-check" class="h-5 w-5" />
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-emerald-900">Profil lengkap</p>
                                    <p class="mt-0.5 text-xs text-emerald-800/80">Semua data penting sudah terisi. Profil siap tampil profesional.</p>
                                </div>
                            </div>
                        </div>
                    @endunless

                    <div class="gh-app-card hidden lg:block">
                        <p class="gh-app-eyebrow mb-2">Ringkasan</p>
                        <div class="gh-profile-stat-row">
                            <div class="gh-app-stat !p-2.5">
                                <p class="gh-app-stat-value text-base">{{ $user->teachingSubjects->count() }}</p>
                                <p class="gh-app-stat-label">Mapel</p>
                            </div>
                            <div class="gh-app-stat !p-2.5">
                                <p class="gh-app-stat-value text-base">{{ count($skills ?? []) }}</p>
                                <p class="gh-app-stat-label">Keahlian</p>
                            </div>
                            <div class="gh-app-stat !p-2.5">
                                <p class="gh-app-stat-value text-base">{{ $publishedCoursesCount }}</p>
                                <p class="gh-app-stat-label">Kelas</p>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Main content tabs --}}
                <div class="gh-profile-main">
                    <div class="gh-profile-tabs" role="tablist">
                        <button type="button" role="tab" @click="profileTab = 'ringkasan'"
                            :class="profileTab === 'ringkasan' ? 'gh-profile-tab gh-profile-tab--active' : 'gh-profile-tab'">
                            Ringkasan
                        </button>
                        <button type="button" role="tab" @click="profileTab = 'mengajar'"
                            :class="profileTab === 'mengajar' ? 'gh-profile-tab gh-profile-tab--active' : 'gh-profile-tab'">
                            Mapel & keahlian
                        </button>
                        <button type="button" role="tab" @click="profileTab = 'rekening'"
                            :class="profileTab === 'rekening' ? 'gh-profile-tab gh-profile-tab--active' : 'gh-profile-tab'">
                            Rekening & dokumen
                        </button>
                    </div>

                    {{-- Tab: Ringkasan --}}
                    <div class="gh-app-card mt-3" x-show="profileTab === 'ringkasan'" x-cloak>
                        <div class="mb-4 flex items-center justify-between gap-3 border-b border-[#0A1A4F]/[0.06] pb-3">
                            <div>
                                <h2 class="gh-app-heading">Data pribadi</h2>
                                <p class="gh-app-caption mt-0.5">Informasi yang tampil untuk siswa & admin</p>
                            </div>
                            @if ($hasProfile)
                                <button type="button" onclick="openModal('editProfileModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm hidden sm:inline-flex">
                                    <x-ui.lucide name="edit" class="h-3.5 w-3.5" /> Edit
                                </button>
                            @endif
                        </div>
                        <div class="gh-profile-field-grid">
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">Nama lengkap</span>
                                <p class="gh-profile-field-value">{{ $user->name }}</p>
                            </div>
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">Telepon / WhatsApp</span>
                                <p class="gh-profile-field-value">{{ $user->phone_number ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">Email akun</span>
                                <p class="gh-profile-field-value">{{ $user->email }}</p>
                            </div>
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">Jenis kelamin</span>
                                <p class="gh-profile-field-value">{{ TeacherProfileChecklist::genderLabel($profile->gender ?? null) }}</p>
                            </div>
                            <div class="gh-profile-field sm:col-span-2">
                                <span class="gh-profile-field-label">Headline profesional</span>
                                <p class="gh-profile-field-value">{{ $profile->title ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="gh-profile-field sm:col-span-2">
                                <span class="gh-profile-field-label">Biografi & deskripsi mengajar</span>
                                <div class="gh-profile-field-value gh-profile-field-value--multiline">
                                    {{ $profile->bio ?? 'Ceritakan pengalaman dan gaya mengajar Anda agar siswa lebih percaya.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Mapel --}}
                    <div class="gh-app-card mt-3" x-show="profileTab === 'mengajar'" x-cloak>
                        <div class="mb-4 flex items-center justify-between gap-3 border-b border-[#0A1A4F]/[0.06] pb-3">
                            <div>
                                <h2 class="gh-app-heading">Mapel & keahlian</h2>
                                <p class="gh-app-caption mt-0.5">Menentukan kelas apa yang bisa Anda buat</p>
                            </div>
                            @if ($hasProfile)
                                <button type="button" onclick="openModal('editProfileModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                                    <x-ui.lucide name="plus" class="h-3.5 w-3.5" /> Atur mapel
                                </button>
                            @endif
                        </div>

                        @if ($user->teachingSubjects->isNotEmpty())
                            <div class="gh-subject-picker-levels !max-h-none">
                                @foreach ($subjectGroups as $levelName => $subjects)
                                    <div class="gh-subject-level">
                                        <div class="gh-subject-level-trigger !cursor-default">
                                            <span class="gh-subject-level-icon">{{ $subjects->first()->educationLevel?->icon }}</span>
                                            <span class="gh-subject-level-copy">
                                                <span class="gh-subject-level-name">{{ $levelName }}</span>
                                                <span class="gh-subject-level-meta">{{ $subjects->count() }} mapel diampu</span>
                                            </span>
                                            <span class="gh-subject-level-badge">{{ $subjects->count() }}</span>
                                        </div>
                                        <div class="gh-subject-level-panel">
                                            <div class="gh-subject-chip-grid">
                                                @foreach ($subjects as $subject)
                                                    @php
                                                        $categoryName = $subject->category?->name;
                                                        $showCategory = filled($categoryName) && strcasecmp($categoryName, $subject->name) !== 0;
                                                    @endphp
                                                    <div class="gh-subject-chip gh-subject-chip--readonly">
                                                        <span class="gh-subject-chip-check gh-subject-chip-check--on">
                                                            <x-ui.lucide name="shield-check" class="h-3.5 w-3.5" />
                                                        </span>
                                                        <span class="gh-subject-chip-body">
                                                            <span class="gh-subject-chip-title">{{ $subject->name }}</span>
                                                            @if ($showCategory)
                                                                <span class="gh-subject-chip-meta">{{ $categoryName }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <x-app.empty-state icon="book-open" title="Belum ada mapel" description="Pilih jenjang dan mata pelajaran yang Anda ampu agar bisa membuat kelas.">
                                <button type="button" onclick="openModal('{{ $hasProfile ? 'editProfileModal' : 'addProfileModal' }}')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-4">
                                    Pilih mapel sekarang
                                </button>
                            </x-app.empty-state>
                        @endif

                        <div class="mt-5 border-t border-[#0A1A4F]/[0.06] pt-4">
                            <p class="gh-profile-field-label mb-2">Tag keahlian</p>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($skills ?? [] as $tag)
                                    <span class="rounded-lg border border-[#0A1A4F]/[0.08] bg-[#f8fafc] px-2.5 py-1 text-[11px] font-bold text-[#475569]">{{ $tag }}</span>
                                @empty
                                    <p class="text-xs text-[#94A3B8] italic">Belum ada tag keahlian.</p>
                                @endforelse
                            </div>
                        </div>

                        @if ($hasProfile && $user->teachingSubjects->isNotEmpty())
                            <div class="mt-4 rounded-xl border border-[#0E7490]/15 bg-[#ecfeff]/50 p-3">
                                <p class="text-xs font-semibold text-[#0A1A4F]">Langkah berikutnya</p>
                                <p class="mt-0.5 text-[11px] text-[#64748B]">Mapel sudah terdaftar — buat kelas pertama Anda di menu Kelola Kelas.</p>
                                <a href="/courses" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-2 inline-flex">Buat kelas</a>
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Rekening & dokumen --}}
                    <div class="gh-app-card mt-3" x-show="profileTab === 'rekening'" x-cloak>
                        <div class="mb-4 flex items-center justify-between gap-3 border-b border-[#0A1A4F]/[0.06] pb-3">
                            <div>
                                <h2 class="gh-app-heading">Rekening & dokumen</h2>
                                <p class="gh-app-caption mt-0.5">Untuk pencairan pendapatan dan verifikasi</p>
                            </div>
                            @if ($hasProfile)
                                <button type="button" onclick="openModal('uploadMediaModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm hidden sm:inline-flex">
                                    <x-ui.lucide name="upload" class="h-3.5 w-3.5" /> Upload
                                </button>
                            @endif
                        </div>

                        <div class="gh-profile-field-grid mb-4">
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">Nama bank</span>
                                <p class="gh-profile-field-value">{{ $profile->bank_name ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="gh-profile-field">
                                <span class="gh-profile-field-label">No. rekening</span>
                                <p class="gh-profile-field-value font-mono">{{ $profile->bank_account_number ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="gh-profile-field sm:col-span-2">
                                <span class="gh-profile-field-label">Nama pemilik rekening</span>
                                <p class="gh-profile-field-value">{{ $profile->bank_account_name ?? 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <p class="gh-profile-field-label">Berkas pendukung</p>
                            @if ($profile->cv_file ?? false)
                                <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank" rel="noopener"
                                    class="flex items-center gap-3 rounded-xl border border-[#0A1A4F]/[0.08] bg-[#f8fafc] p-3 transition hover:border-[#0E7490]/30">
                                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-rose-50 text-rose-500">
                                        <x-ui.lucide name="file-text" class="h-5 w-5" />
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-bold text-[#0A1A4F]">Curriculum Vitae (PDF)</span>
                                        <span class="block text-[11px] text-[#94A3B8]">Ketuk untuk membuka berkas</span>
                                    </span>
                                    <x-ui.lucide name="arrow-right" class="h-4 w-4 text-[#94A3B8]" />
                                </a>
                            @else
                                <div class="rounded-xl border border-dashed border-amber-200 bg-amber-50/50 p-4 text-center">
                                    <p class="text-xs font-semibold text-amber-900">CV belum diunggah</p>
                                    <p class="mt-0.5 text-[11px] text-amber-800/80">Unggah PDF untuk memperkuat kredibilitas profil.</p>
                                    @if ($hasProfile)
                                        <button type="button" onclick="openModal('uploadMediaModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm mt-2">Unggah CV</button>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center gap-3 rounded-xl border border-[#0A1A4F]/[0.08] bg-[#f8fafc] p-3">
                                <x-app.user-avatar :user="$user" size="md" />
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-[#0A1A4F]">Foto profil</p>
                                    <p class="text-[11px] text-[#94A3B8]">
                                        {{ $user->hasCustomAvatar() ? 'Foto kustom sudah diunggah' : 'Masih memakai avatar default' }}
                                    </p>
                                </div>
                                @if ($hasProfile)
                                    <button type="button" onclick="openModal('uploadMediaModal')" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">Ubah</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($hasProfile)
                <div class="gh-app-sticky-cta lg:hidden">
                    <button type="button" onclick="openModal('editProfileModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-block">
                        <x-ui.lucide name="edit" class="h-4 w-4" /> Edit profil pengajar
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('app-modals')
    <div id="addProfileModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true" hidden>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('addProfileModal')" aria-hidden="true"></div>
        <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
            <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white px-5 py-4">
                    <div>
                        <h3 class="text-base font-bold text-[#06122E]">Buat profil pengajar</h3>
                        <p class="text-xs text-[#64748B]">Isi data utama — bisa diperbarui kapan saja</p>
                    </div>
                    <button type="button" onclick="closeModal('addProfileModal')" class="gh-app-icon-btn" aria-label="Tutup">
                        <x-ui.lucide name="x" class="h-4 w-4" />
                    </button>
                </div>
                <div class="space-y-4 p-5">
                    <x-guru.profile-form
                        :user="$user"
                        :profile="$profile"
                        :education-levels="$educationLevels"
                        :selected-subject-ids="$selectedSubjectIds"
                        form-action="{{ url('/teachers') }}"
                        submit-label="Simpan & lanjutkan"
                    />
                </div>
            </div>
        </div>
    </div>

    @if ($hasProfile)
        <div id="editProfileModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true" hidden>
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('editProfileModal')" aria-hidden="true"></div>
            <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
                <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                    <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white px-5 py-4">
                        <div>
                            <h3 class="text-base font-bold text-[#06122E]">Edit profil pengajar</h3>
                            <p class="text-xs text-[#64748B]">Perbarui identitas, mapel, biografi, dan rekening</p>
                        </div>
                        <button type="button" onclick="closeModal('editProfileModal')" class="gh-app-icon-btn" aria-label="Tutup">
                            <x-ui.lucide name="x" class="h-4 w-4" />
                        </button>
                    </div>
                    <div class="space-y-4 p-5">
                        <x-guru.profile-form
                            :user="$user"
                            :profile="$profile"
                            :education-levels="$educationLevels"
                            :selected-subject-ids="$selectedSubjectIds"
                            form-action="{{ url('/teachers/' . $profile->id) }}"
                            form-method="PUT"
                            :show-user-fields="true"
                            submit-label="Simpan perubahan"
                        />
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="uploadMediaModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true" hidden>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('uploadMediaModal')" aria-hidden="true"></div>
        <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
            <div class="w-full max-w-lg rounded-2xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h3 class="text-base font-bold text-[#06122E]">Foto & berkas CV</h3>
                        <p class="text-xs text-[#64748B]">Tampil lebih profesional di katalog guru</p>
                    </div>
                    <button type="button" onclick="closeModal('uploadMediaModal')" class="gh-app-icon-btn" aria-label="Tutup">
                        <x-ui.lucide name="x" class="h-4 w-4" />
                    </button>
                </div>
                <form action="{{ url('/teachers/' . ($profile->id ?? $user->id)) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                    <input type="hidden" name="title" value="{{ $profile->title ?? 'Guru Pengajar' }}">
                    <input type="hidden" name="bio" value="{{ $profile->bio ?? '' }}">
                    <input type="hidden" name="skills_tags" value="{{ ($profile->skills_tags ?? null) ? implode(', ', json_decode($profile->skills_tags, true)) : '' }}">
                    <input type="hidden" name="bank_name" value="{{ $profile->bank_name ?? '' }}">
                    <input type="hidden" name="bank_account_number" value="{{ $profile->bank_account_number ?? '' }}">
                    <input type="hidden" name="bank_account_name" value="{{ $profile->bank_account_name ?? $user->name }}">
                    @foreach ($selectedSubjectIds as $sid)
                        <input type="hidden" name="subject_ids[]" value="{{ $sid }}">
                    @endforeach

                    <div>
                        <label class="gh-profile-form-label">Foto profil</label>
                        <div class="mt-1 rounded-xl border border-[#0A1A4F]/[0.08] bg-[#f8fafc] p-3">
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-indigo-700">
                        </div>
                        <p class="mt-1 text-[10px] text-[#94A3B8]">JPG, PNG, WEBP — maks. 2 MB</p>
                    </div>

                    <div>
                        <label class="gh-profile-form-label">Berkas CV (PDF)</label>
                        <div class="mt-1 rounded-xl border border-[#0A1A4F]/[0.08] bg-[#f8fafc] p-3">
                            <input type="file" name="cv_file" accept="application/pdf,.pdf"
                                class="w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-emerald-700">
                        </div>
                        <p class="mt-1 text-[10px] text-[#94A3B8]">PDF — maks. 3 MB</p>
                    </div>

                    <div class="flex flex-col-reverse gap-2 border-t border-[#0A1A4F]/[0.06] pt-4 sm:flex-row sm:justify-end">
                        <button type="button" onclick="closeModal('uploadMediaModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Batal</button>
                        <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Simpan berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('app-scripts')
    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.removeAttribute('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.setAttribute('hidden', '');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.overflow = '';

            @if ($errors->any() && ! $errors->has('avatar') && ! $errors->has('cv_file'))
                openModal('{{ $hasProfile ? 'editProfileModal' : 'addProfileModal' }}');
            @elseif ($errors->has('avatar') || $errors->has('cv_file'))
                openModal('uploadMediaModal');
            @endif
        });
    </script>
@endpush
