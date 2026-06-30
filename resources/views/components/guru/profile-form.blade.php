@props([
    'user',
    'profile' => null,
    'educationLevels',
    'selectedSubjectIds' => [],
    'formAction',
    'formMethod' => 'POST',
    'submitLabel' => 'Simpan Profil',
    'showUserFields' => false,
])

@php
    $isEdit = $showUserFields && $profile?->id;
@endphp

<form action="{{ $formAction }}" method="POST" @if($attributes->has('enctype')) enctype="multipart/form-data" @endif {{ $attributes->except(['formAction', 'formMethod', 'submitLabel', 'showUserFields']) }}>
    @csrf
    @if (strtoupper($formMethod) !== 'POST')
        @method($formMethod)
    @endif

    @unless($isEdit)
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input type="hidden" name="verification_status" value="pending">
    @endunless

    <div class="gh-profile-form-section">
        <p class="gh-profile-form-section-title"><x-ui.lucide name="user" class="h-3.5 w-3.5" /> Identitas</p>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @if ($isEdit)
                <div class="sm:col-span-2">
                    <label class="gh-profile-form-label">Nama lengkap & gelar</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="gh-app-input w-full">
                </div>
                <div>
                    <label class="gh-profile-form-label">Telepon / WhatsApp</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="gh-app-input w-full" placeholder="08xxxxxxxxxx">
                </div>
            @endif
            <div @class(['sm:col-span-2' => ! $isEdit])>
                <label class="gh-profile-form-label">Jenis kelamin</label>
                <select name="gender" class="gh-app-select w-full">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L" @selected(old('gender', $profile->gender ?? '') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender', $profile->gender ?? '') === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="gh-profile-form-label">Headline profesional</label>
                <input type="text" name="title" value="{{ old('title', $profile->title ?? '') }}"
                    placeholder="Contoh: Guru Matematika SMP · 10 tahun pengalaman"
                    class="gh-app-input w-full">
            </div>
        </div>
    </div>

    <div class="gh-profile-form-section">
        <p class="gh-profile-form-section-title"><x-ui.lucide name="book-open" class="h-3.5 w-3.5" /> Mapel & keahlian</p>
        <div class="space-y-3">
            <div>
                <label class="gh-profile-form-label">Tag keahlian <span class="font-normal text-[#94A3B8]">(pisahkan koma)</span></label>
                <input type="text" name="skills_tags"
                    value="{{ old('skills_tags', ($profile->skills_tags ?? null) ? implode(', ', json_decode($profile->skills_tags, true)) : '') }}"
                    placeholder="Aljabar, UTBK, Kurikulum Merdeka"
                    class="gh-app-input w-full text-[13px]">
            </div>
            <div>
                <label class="gh-profile-form-label">Mata pelajaran per jenjang</label>
                <x-education.subject-picker :levels="$educationLevels" :selected="$selectedSubjectIds" />
        </div>
    </div>

    <div class="gh-profile-form-section">
        <p class="gh-profile-form-section-title"><x-ui.lucide name="file-text" class="h-3.5 w-3.5" /> Cerita pengajar</p>
        <label class="gh-profile-form-label">Biografi singkat</label>
        <textarea name="bio" rows="4" placeholder="Pengalaman mengajar, metode, dan target siswa yang Anda bantu..."
            class="gh-app-input w-full min-h-[100px] resize-y">{{ old('bio', $profile->bio ?? '') }}</textarea>
    </div>

    <div class="gh-profile-form-section">
        <p class="gh-profile-form-section-title"><x-ui.lucide name="credit-card" class="h-3.5 w-3.5" /> Rekening pencairan</p>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div>
                <label class="gh-profile-form-label">Nama bank</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $profile->bank_name ?? '') }}" placeholder="BCA / Mandiri" class="gh-app-input w-full">
            </div>
            <div>
                <label class="gh-profile-form-label">No. rekening</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $profile->bank_account_number ?? '') }}" class="gh-app-input w-full font-mono">
            </div>
            <div>
                <label class="gh-profile-form-label">Nama pemilik</label>
                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $profile->bank_account_name ?? '') }}" class="gh-app-input w-full">
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-2 border-t border-[#0A1A4F]/[0.06] pt-4 sm:flex-row sm:justify-end">
        <button type="button" onclick="closeModal(this.closest('[role=dialog]').id)" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Batal</button>
        <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">{{ $submitLabel }}</button>
    </div>
</form>
