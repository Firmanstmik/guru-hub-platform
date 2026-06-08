@extends('layout.master-app')

@section('content')
    <div class="min-h-screen bg-gray-50 px-4 sm:px-6 lg:px-8">
        {{-- Header Halaman --}}
        <div class="max-w-7xl mx-auto mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight sm:text-3xl">
                Profil Siswa
            </h2>
            <p class="mt-1.5 text-sm text-gray-600">
                Pantau detail biodata Anda atau perbarui informasi profil di bawah ini secara berkala.
            </p>
        </div>

        {{-- Grid Utama Dashboard --}}
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KOLOM KIRI: Detail Biodata Aktif (Card) --}}
            <div class="space-y-6">
                {{-- Card Informasi Akun Utama --}}
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 flex flex-col items-center text-center">
                    {{-- Preview Avatar Siswa --}}
                    <div class="relative mb-4">
                        @if ($user->avatar === 'default-avatar.png')
                            <img src="{{ asset('assets/avatar/' . $user->avatar) }}" alt="Avatar {{ $user->name }}"
                                class="w-24 h-24 rounded-2xl object-cover border-4 border-gray-50 shadow-xs">
                        @else
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar {{ $user->name }}"
                                class="w-24 h-24 rounded-2xl object-cover border-4 border-gray-50 shadow-xs">
                        @endif
                    </div>

                    <div class="space-y-1.5 w-full">
                        <h3 class="text-base font-bold text-gray-900 tracking-tight">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-500 break-all px-2">{{ $user->email }}</p>

                        <div
                            class="pt-3 border-t border-gray-50 mt-3 grid grid-cols-1 gap-2 text-left bg-gray-50/50 p-3 rounded-xl">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Nomor
                                    Telepon</span>
                                <span
                                    class="text-xs font-semibold text-gray-700">{{ $user->phone_number ?? 'Belum diatur' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Ringkasan Biodata Siswa --}}
                <div
                    class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl shadow-md text-white p-6 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-indigo-800/20 pointer-events-none">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                    </div>

                    <h3
                        class="text-sm font-bold uppercase tracking-wider border-b border-indigo-500/30 pb-3 mb-4 text-indigo-200">
                        Detail Biodata Siswa
                    </h3>

                    @if (isset($biodata))
                        <div class="space-y-4 text-xs">
                            <div>
                                <span class="block text-indigo-300 font-medium mb-0.5">Nomor Induk Siswa Nasional
                                    (NISN)</span>
                                <span class="text-sm font-bold tracking-wide">{{ $biodata->nisn }}</span>
                            </div>
                            <div>
                                <span class="block text-indigo-300 font-medium mb-0.5">Asal Sekolah / Kampus</span>
                                <span class="text-sm font-semibold">{{ $biodata->institution_name }}</span>
                            </div>
                            <div>
                                <span class="block text-indigo-300 font-medium mb-0.5">Tanggal Lahir</span>
                                <span
                                    class="text-sm font-semibold">{{ \Carbon\Carbon::parse($biodata->birth_date)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-indigo-300 font-medium mb-0.5">Jenis Kelamin</span>
                                <span
                                    class="text-sm font-semibold">{{ $biodata->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div>
                                <span class="block text-indigo-300 font-medium mb-0.5">Alamat Tempat Tinggal</span>
                                <p class="text-sm text-indigo-100 leading-relaxed">{{ $biodata->address }}</p>
                            </div>
                        </div>
                    @else
                        <div class="py-6 text-center">
                            <p class="text-sm text-indigo-200 italic">Belum ada data pribadi. Silakan lengkapi formulir di
                                sebelah kanan.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN: Form Create / Update (Lebih lebar) --}}
            <div class="lg:col-span-2">
                <div class="bg-white py-8 px-6 shadow-xs border border-gray-100 sm:rounded-2xl sm:px-10">
                    <h3 class="text-base font-bold text-gray-900 mb-5 pb-3 border-b border-gray-50 flex items-center gap-2">
                        <span class="w-2 h-5 bg-indigo-600 rounded-full"></span>
                        {{ isset($biodata) ? 'Perbarui Informasi Profil' : 'Isi Formulir Biodata Baru' }}
                    </h3>

                    {{-- Ditambahkan enctype="multipart/form-data" untuk handle upload file --}}
                    <form action="{{ url('/biodata') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        {{-- SEKSI 1: Akun User (Avatar & Nomor Telepon) --}}
                        <div
                            class="bg-gray-50/50 p-4 rounded-xl border border-gray-100/50 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Input Upload Avatar --}}
                            <div>
                                <label for="avatar"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Foto Profil / Avatar <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <input id="avatar" name="avatar" type="file" accept="image/*"
                                    class="w-full px-3 py-2 text-xs bg-white border @error('avatar') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-500
                                    file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 2MB)</p>
                                @error('avatar')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Nomor Telepon --}}
                            <div>
                                <label for="phone_number"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input id="phone_number" name="phone_number" type="tel"
                                    value="{{ old('phone_number', $user->phone_number ?? '') }}" required
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-4 py-2.5 text-sm bg-white border @error('phone_number') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                @error('phone_number')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- SEKSI 2: Detail Biodata Siswa --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Input NISN --}}
                            <div>
                                <label for="nisn"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    NISN <span class="text-rose-500">*</span>
                                </label>
                                <input id="nisn" name="nisn" type="text"
                                    value="{{ old('nisn', $biodata->nisn ?? '') }}" required
                                    placeholder="Contoh: 0048212345"
                                    class="w-full px-4 py-2.5 text-sm bg-white border @error('nisn') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                @error('nisn')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Nama Sekolah/Instansi --}}
                            <div>
                                <label for="institution_name"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Asal Sekolah / Kampus <span class="text-rose-500">*</span>
                                </label>
                                <input id="institution_name" name="institution_name" type="text"
                                    value="{{ old('institution_name', $biodata->institution_name ?? '') }}" required
                                    placeholder="Contoh: SMAN 1 Mataram"
                                    class="w-full px-4 py-2.5 text-sm bg-white border @error('institution_name') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                @error('institution_name')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Tanggal Lahir --}}
                            <div>
                                <label for="birth_date"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Tanggal Lahir <span class="text-rose-500">*</span>
                                </label>
                                <input id="birth_date" name="birth_date" type="date"
                                    value="{{ old('birth_date', $biodata->birth_date ?? '') }}" required
                                    class="w-full px-4 py-2.5 text-sm bg-white border @error('birth_date') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                @error('birth_date')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Jenis Kelamin --}}
                            <div>
                                <label for="gender"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Jenis Kelamin <span class="text-rose-500">*</span>
                                </label>
                                <select id="gender" name="gender" required
                                    class="w-full px-4 py-2.5 text-sm bg-white border @error('gender') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                    <option value="" disabled
                                        {{ old('gender', $biodata->gender ?? '') == '' ? 'selected' : '' }}>Pilih Jenis
                                        Kelamin</option>
                                    <option value="L"
                                        {{ old('gender', $biodata->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P"
                                        {{ old('gender', $biodata->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('gender')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Alamat Tempat Tinggal --}}
                        <div>
                            <label for="address"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                Alamat Rumah Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="4" required
                                placeholder="Tuliskan alamat jalan, RT/RW, desa, dan kabupaten tempat tinggal Anda saat ini"
                                class="w-full px-4 py-2.5 text-sm bg-white border @error('address') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700 resize-none">{{ old('address', $biodata->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit Dinamis --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                {{ isset($biodata) ? 'Simpan Perubahan Profil' : 'Simpan & Selesaikan Pendaftaran' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
