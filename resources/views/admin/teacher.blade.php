@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Verifikasi Profil Pengajar</h3>
            <p class="text-sm text-gray-500 mt-1">Validasi berkas CV, data perbankan, dan kompetensi keahlian calon guru Guru Hub.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="/teachers" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, gelar, keahlian..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Butuh Review)</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Aktif)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama & Gelar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Keahlian & Tag</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Dokumen CV</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Informasi Bank</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($profiles as $profile)
                    <tr class="hover:bg-gray-50 transition duration-150 items-center">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $profile->user->name }}</div>
                            <div class="text-xs text-indigo-600 font-medium">{{ $profile->title ?? 'Pengajar Mandiri' }}</div>
                            <p class="text-xxs text-gray-400 mt-1 max-w-[180px] truncate" title="{{ $profile->bio }}">{{ $profile->bio }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @if($profile->skills_tags)
                                    @foreach(explode(',', $profile->skills_tags) as $tag)
                                        <span class="bg-gray-100 text-gray-700 text-xxs px-2 py-0.5 rounded font-mono font-medium">{{ trim($tag) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada tag</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($profile->cv_file)
                                <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 font-semibold hover:underline bg-indigo-50 px-2 py-1 rounded">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z"/></svg>
                                    Buka Dokumen CV
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum melampirkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                            <div class="font-semibold text-gray-800">{{ $profile->bank_name }}</div>
                            <div class="font-mono text-gray-600">{{ $profile->bank_account_number }}</div>
                            <div class="text-xxs text-gray-400">A.n: {{ $profile->bank_account_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-0.5 text-amber-500 font-bold">
                                <span>★</span>
                                <span>{{ number_format($profile->average_rating, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($profile->verification_status === 'approved')
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">Approved</span>
                            @elseif($profile->verification_status === 'pending')
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-rose-100 text-rose-800 rounded-full">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                            @if($profile->verification_status === 'pending')
                                <div class="flex justify-end gap-1.5">
                                    <form action="/teachers/{{ $profile->id }}/verify" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="verification_status" value="approved">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1.5 rounded text-xxs font-bold tracking-wide uppercase shadow-xs">Terima</button>
                                    </form>

                                    <form action="/teachers/{{ $profile->id }}/verify" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="verification_status" value="rejected">
                                        <button type="submit" class="bg-rose-100 hover:bg-rose-200 text-rose-700 px-2.5 py-1.5 rounded text-xxs font-bold tracking-wide uppercase" onclick="return confirm('Tolak pendaftaran guru ini?')">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xxs text-gray-400">Telah Diverifikasi<br>{{ $profile->updated_at->format('d M Y') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada pengajuan data profil guru baru ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $profiles->links() }}
        </div>
    </div>
</div>
@endsection