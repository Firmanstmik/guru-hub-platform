@extends('layout.master-app')

@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner space-y-4">
            <x-app.page-header title="Pengaturan Profil Pengajar" subtitle="Kelola informasi publik Anda, sertifikasi kompetensi, serta kredensial akun bank pencairan." eyebrow="Ruang Pengajar">
                <x-slot:action>
                    @if (is_null($profile) || !$profile->id)
                        <button type="button" onclick="openModal('addProfileModal')"
                            class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                            Buat Profil Pengajar
                        </button>
                    @endif
                </x-slot:action>
            </x-app.page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

            <div class="gh-app-card relative overflow-hidden p-6 text-center space-y-6">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#0A1A4F] via-[#0E7490] to-[#C9A227]"></div>

                <div class="space-y-4 pt-2">
                    <div class="relative mx-auto w-fit group">
                        <x-app.user-avatar :user="$user" size="xl" class="mx-auto" />
                        @if($profile->id)
                        <button type="button" onclick="openModal('uploadMediaModal')"
                            class="gh-app-btn gh-app-btn-primary absolute -bottom-1 -right-1 !min-h-0 !h-8 !w-8 !rounded-xl !p-0 shadow-lg border-2 border-white"
                            title="Upload Foto / CV">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                            </svg>
                        </button>
                        @else
                            <p class="text-red-500 text-sm">Lengkapi Profile terlebih dahulu</p>
                        @endif
                    </div>

                    <div>
                        <h2
                            class="text-base font-black text-gray-900 flex items-center justify-center gap-1.5 leading-tight">
                            {{ $user->name }}
                            @if (($profile->verification_status ?? 'pending') === 'approved')
                                <span class="text-sky-500 bg-sky-50 rounded-full p-0.5" title="Verified Instructor">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path
                                            d="M6.267 3.455a.75.75 0 00-.708-.523.75.75 0 00-.507.291L1.5 7.625l-.002.002a.75.75 0 00-.03 1.026l3.6 4a.75.75 0 001.121-.013l8.4-9.6a.75.75 0 00-.113-1.054l-8.209 6.471-3.003-3.339 3.003-3.34z" />
                                    </svg>
                                </span>
                            @endif
                        </h2>
                        <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1">
                            {{ $profile->title ?? 'Instruktur Pengajar' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        <span
                            class="px-2.5 py-0.5 text-[10px] font-black rounded-lg uppercase tracking-wider 
                        {{ ($profile->verification_status ?? 'pending') === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (($profile->verification_status ?? 'pending') === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                            {{ $profile->verification_status ?? 'pending' }}
                        </span>
                        <span
                            class="px-2.5 py-0.5 text-[10px] font-black bg-gray-50 border border-gray-100 text-gray-700 rounded-lg flex items-center gap-1">
                            ⭐ {{ number_format($profile->average_rating ?? 0, 1) }}
                        </span>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="space-y-2 text-left">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Keahlian Utama</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($skills ?? [] as $tag)
                            <span
                                class="px-2 py-1 bg-gray-50 text-gray-600 font-bold border border-gray-100 rounded-lg text-[10px]">{{ $tag }}</span>
                        @empty
                            <span class="text-xs text-gray-400 italic">Belum menambahkan tag keahlian.</span>
                        @endforelse
                    </div>
                </div>

                <div class="pt-2 text-left border-t border-gray-50">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Berkas Pendukung</h4>
                    @if (isset($profile) && $profile->cv_file)
                        <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank"
                            class="inline-flex items-center gap-2 p-2.5 w-full bg-gray-50 border border-gray-100 hover:border-indigo-200 text-xs font-bold text-gray-700 rounded-xl transition group">
                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="truncate flex-1 group-hover:text-indigo-600">Lihat Lampiran CV (PDF)</span>
                        </a>
                    @else
                        <p class="text-xs text-amber-600 bg-amber-50/50 p-2.5 rounded-xl border border-amber-100 italic">
                            Dokumen berkas CV belum diunggah.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-2xs space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Detail Ringkasan Profil
                            </h3>
                            <p class="text-[11px] text-gray-400 mt-0.5">Berikut adalah rincian data Anda yang terdaftar pada
                                sistem.</p>
                        </div>
                        @if (isset($profile) && $profile->id)
                            <button type="button" onclick="openModal('editProfileModal')"
                                class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit Profil
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Lengkap
                                & Gelar</span>
                            <p
                                class="text-xs font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                {{ $user->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nomor Telepon
                                / WhatsApp</span>
                            <p
                                class="text-xs font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                {{ $user->phone_number ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Headline
                                Profesional</span>
                            <p
                                class="text-xs font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                {{ $profile->title ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Biografi &
                                Deskripsi Pengajaran</span>
                            <div
                                class="text-xs font-medium text-gray-600 bg-gray-50 px-3 py-3 rounded-xl border border-gray-100 leading-relaxed whitespace-pre-line">
                                {{ $profile->bio ?? 'Anda belum menuliskan biografi singkat pengajaran Anda.' }}
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5 space-y-4">
                        <h4 class="text-xs font-black text-gray-900 uppercase tracking-wider">Kredensial Rekening Pencairan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama
                                    Bank</span>
                                <p
                                    class="text-xs font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                    {{ $profile->bank_name ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nomor
                                    Rekening</span>
                                <p
                                    class="text-xs font-mono font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                    {{ $profile->bank_account_number ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Pemilik
                                    Rekening</span>
                                <p
                                    class="text-xs font-bold text-gray-800 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-100">
                                    {{ $profile->bank_account_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div id="addProfileModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity"
                onclick="closeModal('addProfileModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                <div class="bg-white px-5 pt-5 pb-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Lengkapi Profile</h3>
                        <button type="button" onclick="closeModal('addProfileModal')"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ url('/teachers') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="verification_status" value="pending">

                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-600">Judul Profesional / Headline</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                placeholder="Contoh: Senior Fullstack Engineer & Informatics Educator"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-600">Keahlian Tags (Pisahkan Dengan Koma)</label>
                            <input type="text" name="skills_tags" value="{{ old('skills_tags') }}"
                                placeholder="Laravel, Kotlin, Unit Testing"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-600">Biografi Singkat</label>
                            <textarea name="bio" rows="3" placeholder="Jelaskan pengalaman industri Anda..."
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs focus:bg-white focus:border-indigo-500 focus:outline-none resize-none">{{ old('bio') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                            <div>
                                <label class="text-xs font-black text-gray-600">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                    placeholder="BCA/Mandiri"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs focus:outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-black text-gray-600">No. Rekening</label>
                                <input type="text" name="bank_account_number"
                                    value="{{ old('bank_account_number') }}" placeholder="04128912"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs font-mono focus:outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-black text-gray-600">Nama Pemilik Akun</label>
                                <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}"
                                    placeholder="Sesuai buku tabungan"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs focus:outline-none">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3 mt-4">
                            <button type="button" onclick="closeModal('addProfileModal')"
                                class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-5 py-2.5 rounded-xl text-xs shadow-xs uppercase tracking-wider">Simpan
                                Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($profile) && $profile->id)
        <div id="editProfileModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity"
                    onclick="closeModal('editProfileModal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <div class="bg-white px-5 pt-5 pb-4 sm:p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Edit Informasi Profil
                                Anda</h3>
                            <button type="button" onclick="closeModal('editProfileModal')"
                                class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ url('/teachers/' . $profile->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-black text-gray-600">Nama Lengkap & Gelar</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="text-xs font-black text-gray-600">Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Judul Profesional / Headline</label>
                                <input type="text" name="title" value="{{ old('title', $profile->title ?? '') }}"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Keahlian Tags (Pisahkan Dengan
                                    Koma)</label>
                                <input type="text" name="skills_tags"
                                    value="{{ old('skills_tags', $profile->skills_tags ? implode(', ', json_decode($profile->skills_tags, true)) : '') }}"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:bg-white focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Biografi Singkat</label>
                                <textarea name="bio" rows="4"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs focus:bg-white focus:border-indigo-500 focus:outline-none resize-none">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t border-gray-50 pt-3">
                                <div>
                                    <label class="text-xs font-black text-gray-600">Nama Bank</label>
                                    <input type="text" name="bank_name"
                                        value="{{ old('bank_name', $profile->bank_name ?? '') }}"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs focus:outline-none">
                                </div>
                                <div>
                                    <label class="text-xs font-black text-gray-600">No. Rekening</label>
                                    <input type="text" name="bank_account_number"
                                        value="{{ old('bank_account_number', $profile->bank_account_number ?? '') }}"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs font-mono focus:outline-none">
                                </div>
                                <div>
                                    <label class="text-xs font-black text-gray-600">Nama Pemilik Akun</label>
                                    <input type="text" name="bank_account_name"
                                        value="{{ old('bank_account_name', $profile->bank_account_name ?? '') }}"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs focus:outline-none">
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3 mt-4">
                                <button type="button" onclick="closeModal('editProfileModal')"
                                    class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-5 py-2.5 rounded-xl text-xs shadow-xs uppercase tracking-wider">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="uploadMediaModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity"
                onclick="closeModal('uploadMediaModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-5 pt-5 pb-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Perbarui File Media</h3>
                        <button type="button" onclick="closeModal('uploadMediaModal')"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ url('/teachers/' . ($profile->id ?? $user->id)) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                        <input type="hidden" name="title" value="{{ $profile->title ?? 'Instructor' }}">
                        <input type="hidden" name="bio" value="{{ $profile->bio ?? '' }}">
                        <input type="hidden" name="skills_tags"
                            value="{{ isset($profile) && $profile->skills_tags ? implode(', ', json_decode($profile->skills_tags, true)) : '' }}">
                        <input type="hidden" name="bank_name" value="{{ $profile->bank_name ?? 'Mandiri' }}">
                        <input type="hidden" name="bank_account_number"
                            value="{{ $profile->bank_account_number ?? '00000' }}">
                        <input type="hidden" name="bank_account_name"
                            value="{{ $profile->bank_account_name ?? $user->name }}">

                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-600 block">Ubah Foto Profil (Avatar)</label>
                            <div class="mt-1 flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <input type="file" name="avatar"
                                    class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-700 cursor-pointer">
                            </div>
                            <span class="text-[10px] text-gray-400 block">Ekstensi: JPG, JPEG, PNG (Maks 2MB).</span>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-black text-gray-600 block">Unggah Berkas Curriculum Vitae
                                Baru</label>
                            <div class="mt-1 flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <input type="file" name="cv_file"
                                    class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:bg-emerald-50 file:text-emerald-700 cursor-pointer">
                            </div>
                            <span class="text-[10px] text-gray-400 block">Ekstensi: PDF (Maksimal 3MB).</span>
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3">
                            <button type="button" onclick="closeModal('uploadMediaModal')"
                                class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-4 py-2 rounded-xl text-xs shadow-xs transition">Simpan
                                Berkas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Otomatis popup kembali modal jika ada error validasi saat upload berkas
        @if ($errors->has('avatar') || $errors->has('cv_file'))
            document.addEventListener("DOMContentLoaded", function() {
                openModal('uploadMediaModal');
            });
        @endif
    </script>
@endsection
