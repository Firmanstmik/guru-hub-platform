@extends('layout.master-app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-[1600px]">
    
    <div class="mb-8 space-y-1">
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <span>Ruang Pengajar</span>
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 font-bold">Profil Saya</span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pengaturan Profil Pengajar</h1>
        <p class="text-xs text-gray-400">Kelola informasi publik Anda, sertifikasi kompetensi, serta kredensial akun bank pencairan.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-xs font-bold text-emerald-800 flex items-center gap-3 shadow-3xs animate-fade-in">
            <div class="p-1.5 bg-emerald-500 text-white rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-2xs space-y-6 text-center relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

            <div class="space-y-4 pt-2">
                <div class="relative w-28 h-28 mx-auto group">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&size=128' }}" 
                         alt="Avatar {{ $user->name }}" class="w-full h-full object-cover rounded-full border-4 border-gray-50 shadow-md transition group-hover:brightness-90">
                    <button type="button" onclick="openUploadModal()" class="absolute bottom-0 right-0 p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md border-2 border-white transition transform hover:scale-105" title="Ubah Foto / CV">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                    </button>
                </div>

                <div>
                    <h2 class="text-base font-black text-gray-900 flex items-center justify-center gap-1.5 leading-tight">
                        {{ $user->name }}
                        @if(($profile->verification_status ?? 'pending') === 'approved')
                            <span class="text-sky-500 bg-sky-50 rounded-full p-0.5" title="Verified Instructor">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M6.267 3.455a.75.75 0 00-.708-.523.75.75 0 00-.507.291L1.5 7.625l-.002.002a.75.75 0 00-.03 1.026l3.6 4a.75.75 0 001.121-.013l8.4-9.6a.75.75 0 00-.113-1.054l-8.209 6.471-3.003-3.339 3.003-3.34z"/></svg>
                            </span>
                        @endif
                    </h2>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1">{{ $profile->title ?? 'Instruktur Pengajar' }}</p>
                </div>
                
                <div class="flex items-center justify-center gap-2">
                    <span class="px-2.5 py-0.5 text-[10px] font-black rounded-lg uppercase tracking-wider 
                        {{ ($profile->verification_status ?? 'pending') === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (($profile->verification_status ?? 'pending') === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                        {{ $profile->verification_status ?? 'pending' }}
                    </span>
                    <span class="px-2.5 py-0.5 text-[10px] font-black bg-gray-50 border border-gray-100 text-gray-700 rounded-lg flex items-center gap-1">
                        ⭐ {{ number_format($profile->average_rating ?? 0, 1) }}
                    </span>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="space-y-2 text-left">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Keahlian Utama</h4>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($skills as $tag)
                        <span class="px-2 py-1 bg-gray-50 text-gray-600 font-bold border border-gray-100 rounded-lg text-[10px]">{{ $tag }}</span>
                    @empty
                        <span class="text-xs text-gray-400 italic">Belum menambahkan tag keahlian.</span>
                    @endforelse
                </div>
            </div>

            <div class="pt-2 text-left border-t border-gray-50">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Berkas Pendukung</h4>
                @if($profile->cv_file)
                    <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank" class="inline-flex items-center gap-2 p-2.5 w-full bg-gray-50 border border-gray-100 hover:border-indigo-200 text-xs font-bold text-gray-700 rounded-xl transition group">
                        <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span class="truncate flex-1 group-hover:text-indigo-600">Lihat Lampiran CV (PDF)</span>
                    </a>
                @else
                    <p class="text-xs text-amber-600 bg-amber-50/50 p-2.5 rounded-xl border border-amber-100 italic">Dokumen berkas CV belum diunggah.</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2">
            <form action="{{ url('/teachers/' . $profile->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-50 pb-3">
                            <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">A. Data Personal Dasar</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Nama Lengkap & Gelar</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('name') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('phone_number') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-50 pb-3">
                            <div class="p-1.5 bg-purple-50 pointer-events-none text-purple-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.142s.512-.64.734-.842m14.746 0c.223.2.735.842.735.842a37.322 37.322 0 00-4.143-6.523 2.191 2.191 0 00-1.393-.733 50.154 50.154 0 00-13.914 0 2.19 2.19 0 00-1.393.733 37.306 37.306 0 00-4.143 6.523zm14.746 0a40.231 40.231 0 01-14.747 0m14.746 0l-1.348 8.1c-.139.832-.862 1.442-1.701 1.442H5.064c-.84 0-1.562-.61-1.701-1.442l-1.348-8.1m14.746 0H4.26" />
                                </svg>
                            </div>
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">B. Portofolio & Keahlian Profesional</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Judul Profesional / Headline</label>
                                <input type="text" name="title" value="{{ old('title', $profile->title) }}" placeholder="Contoh: Senior Fullstack Engineer & Informatics Educator" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('title') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Keahlian Tags (Pisahkan Dengan Tanda Koma)</label>
                                <input type="text" name="skills_tags" value="{{ old('skills_tags', $profile->skills_tags ? implode(', ', json_decode($profile->skills_tags, true)) : '') }}" placeholder="Laravel, Kotlin, Unit Testing, Docker" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                <span class="text-[10px] text-gray-400 block mt-0.5">Sistem otomatis memecah kata kunci ini menjadi susunan badge keahlian.</span>
                                @error('skills_tags') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Biografi Singkat (Profil Singkat)</label>
                                <textarea name="bio" rows="4" placeholder="Jelaskan pengalaman industri Anda, riset kurikulum, atau fokus pengajaran yang Anda kuasai..." class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition resize-none">{{ old('bio', $profile->bio) }}</textarea>
                                @error('bio') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-50 pb-3">
                            <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M4.5 19.5h15M5.25 4.5v15m13.5-15v15M6.75 7.5h10.5M6.75 10.5h10.5M6.75 13.5h10.5M6.75 16.5h10.5" />
                                </svg>
                            </div>
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">C. Informasi Rekening Bank</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $profile->bank_name) }}" placeholder="Contoh: Mandiri / BCA / BNI" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('bank_name') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Nomor Rekening</label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $profile->bank_account_number) }}" placeholder="Contoh: 041289129" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('bank_account_number') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-black text-gray-600">Nama Pemilik Akun Bank</label>
                                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $profile->bank_account_name) }}" placeholder="Harus sesuai buku tabungan" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('bank_account_name') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3 rounded-xl shadow-sm transition text-xs tracking-wider uppercase">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="uploadMediaModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity" aria-hidden="true" onclick="closeUploadModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-slide-up">
            <div class="bg-white px-5 pt-5 pb-4 sm:p-6 sm:pb-4 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider" id="modal-title">Perbarui File Media</h3>
                    <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ url('/teachers/' . $profile->id) }} " method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                    <input type="hidden" name="title" value="{{ $profile->title }}">
                    <input type="hidden" name="bio" value="{{ $profile->bio }}">
                    <input type="hidden" name="skills_tags" value="{{ $profile->skills_tags ? implode(', ', json_decode($profile->skills_tags, true)) : '' }}">
                    <input type="hidden" name="bank_name" value="{{ $profile->bank_name }}">
                    <input type="hidden" name="bank_account_number" value="{{ $profile->bank_account_number }}">
                    <input type="hidden" name="bank_account_name" value="{{ $profile->bank_account_name }}">

                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-600 block">Ubah Foto Profil (Avatar)</label>
                        <div class="mt-1 flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="file" name="avatar" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        </div>
                        <span class="text-[10px] text-gray-400 block">Ekstensi yang diizinkan: JPG, JPEG, PNG (Maks 2MB).</span>
                        @error('avatar') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-600 block">Unggah Berkas Curriculum Vitae Baru</label>
                        <div class="mt-1 flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="file" name="cv_file" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                        <span class="text-[10px] text-gray-400 block">Ekstensi dokumen wajib: PDF (Maksimal ukuran 3MB).</span>
                        @error('cv_file') <p class="text-[10px] text-rose-600 font-bold mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3">
                        <button type="button" onclick="closeUploadModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-4 py-2 rounded-xl text-xs shadow-xs transition">
                            Simpan Berkas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openUploadModal() {
        const modal = document.getElementById('uploadMediaModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUploadModal() {
        const modal = document.getElementById('uploadMediaModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    @if ($errors->has('avatar') || $errors->has('cv_file'))
        document.addEventListener("DOMContentLoaded", function() {
            openUploadModal();
        });
    @endif
</script>
@endsection