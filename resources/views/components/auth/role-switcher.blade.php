@props(['active' => 'student'])

<div class="gh-auth-role-tabs" role="tablist" aria-label="Jenis pendaftaran">
    <a href="{{ url('register/student') }}" role="tab"
        @class(['gh-auth-role-tab', 'gh-auth-role-tab-active' => $active === 'student'])
        @if ($active === 'student') aria-current="page" @endif>
        <x-ui.lucide name="book-open" class="h-4 w-4" />
        Siswa
    </a>
    <a href="{{ url('register/teacher') }}" role="tab"
        @class(['gh-auth-role-tab', 'gh-auth-role-tab-active' => $active === 'teacher'])
        @if ($active === 'teacher') aria-current="page" @endif>
        <x-ui.lucide name="award" class="h-4 w-4" />
        Pengajar
    </a>
</div>
