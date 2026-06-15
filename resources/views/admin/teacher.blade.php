@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Verifikasi Profil Pengajar</h3>
                <p class="text-sm text-gray-500 mt-1">Validasi berkas CV, data perbankan, dan kompetensi keahlian calon guru Hub.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/teachers" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto flex-1">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, gelar, keahlian..."
                            class="bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        @if (request('search'))
                            <a href="/teachers?status={{ request('status') }}"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 text-xs">✕</a>
                        @endif
                    </div>
                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none w-full sm:w-auto">
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
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & Gelar</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keahlian & Tag</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen CV</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi Bank</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @forelse($profiles as $profile)
                            <tr class="hover:bg-gray-50/70 transition duration-150">
                                <td class="px-6 py-4 cursor-pointer"
                                    onclick="openShowModal({{ json_encode($profile) }}, {{ json_encode($profile->user) }})">
                                    <div class="font-bold text-gray-900 text-base hover:text-indigo-600 transition">
                                        {{ $profile->user->name }}</div>
                                    <div class="text-xs text-indigo-600 font-semibold tracking-wide">
                                        {{ $profile->title ?? 'Pengajar Mandiri' }}</div>
                                    <p class="text-xs text-gray-400 mt-1 max-w-[200px] truncate">
                                        {{ $profile->bio ?? 'Belum mengisi deskripsi bio.' }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-[220px]">
                                        @if ($profile->skills_tags)
                                            @foreach (explode(',', $profile->skills_tags) as $tag)
                                                <span class="bg-gray-100 text-gray-700 text-[10px] px-2 py-0.5 rounded font-mono font-medium">{{ trim($tag) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak ada tag</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($profile->cv_file)
                                        <div class="flex flex-col gap-1.5">
                                            <button type="button"
                                                onclick="openCvModal('{{ asset('storage/' . $profile->cv_file) }}', '{{ $profile->user->name }}')"
                                                class="inline-flex items-center gap-1.5 text-xs text-indigo-600 font-semibold hover:bg-indigo-100/80 bg-indigo-50 px-2.5 py-1.5 rounded-lg transition text-left w-max">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Pratinjau CV
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum diunggah</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <div class="font-bold text-gray-800">{{ $profile->bank_name }}</div>
                                    <div class="font-mono text-gray-600 font-medium my-0.5">{{ $profile->bank_account_number }}</div>
                                    <div class="text-[10px] text-gray-400 truncate max-w-[150px]">A.n: {{ $profile->bank_account_name }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1 text-amber-500 font-bold bg-amber-50 px-2 py-1 rounded-md w-max">
                                        <span class="text-sm">★</span>
                                        <span class="text-xs">{{ number_format($profile->average_rating, 1) }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($profile->verification_status === 'approved')
                                        <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-full">Approved</span>
                                    @elseif($profile->verification_status === 'pending')
                                        <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold bg-rose-100 text-rose-800 rounded-full">Rejected</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex flex-center justify-end gap-3">
                                        <button data-profile='@json($profile)' data-user='@json($profile->user)' onclick="handleShowModal(this)"
                                            class="text-blue-600 hover:text-blue-900">Show</button>

                                        <form action="/teachers/{{ $profile->id }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data profil guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-400">Tidak ada pengajuan berkas pengajar ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($profiles->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $profiles->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL VIEW CV ATTACHMENT (POPUP PDF)       --}}
    {{-- ========================================== --}}
    <div id="viewCvModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" onclick="toggleModal('viewCvModal')">
                <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full h-[85vh]">
                <div class="flex flex-col h-full bg-white">
                    <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Berkas Curriculum Vitae (CV)</h3>
                            <p id="cv_modal_title" class="text-xs text-gray-500 mt-0.5"></p>
                        </div>
                        <button onclick="toggleModal('viewCvModal')"
                            class="text-gray-400 hover:text-gray-600 font-semibold p-1 text-sm bg-gray-200/60 rounded-full h-7 w-7 flex items-center justify-center">&times;</button>
                    </div>

                    <div class="flex-1 bg-gray-100 p-2">
                        <iframe id="cv_iframe" src="" class="w-full h-full rounded border border-gray-200" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL BERKAS (SHOW) --}}
    <div id="showTeacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 py-5 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <h3 class="text-lg font-bold text-gray-900">Detail Berkas Pengajar</h3>
                        <button onclick="toggleModal('showTeacherModal')" class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    <div class="bg-indigo-50/60 p-4 rounded-xl">
                        <h4 id="show_user_name" class="font-bold text-gray-900 text-base"></h4>
                        <p id="show_user_email" class="text-xs text-gray-500"></p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div><span class="text-gray-400 text-xs block">Kompetensi/Gelar</span>
                            <p id="show_title" class="font-semibold text-gray-800"></p>
                        </div>
                        <div><span class="text-gray-400 text-xs block">Deskripsi Bio</span>
                            <p id="show_bio" class="text-gray-700 bg-gray-50 p-2 rounded-lg text-xs"></p>
                        </div>
                        <div><span class="text-gray-400 text-xs block">Keahlian</span>
                            <p id="show_skills" class="font-mono text-xs text-indigo-700 bg-indigo-50 px-2 py-1 rounded inline-block mt-1"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t">
                            <div><span class="text-gray-400 text-xs block">Informasi Finansial</span>
                                <p id="show_bank_info" class="font-bold text-gray-800 text-xs"></p>
                                <p id="show_bank_owner" class="text-[10px] text-gray-500"></p>
                            </div>
                            <div><span class="text-gray-400 text-xs block">Rating</span>
                                <p id="show_rating" class="font-bold text-amber-500 text-xs"></p>
                            </div>
                        </div>
                    </div>

                    <div id="show_verify_container" class="flex gap-3 mt-4">
                        <form id="showVerifyApproveForm" method="POST" class="flex-1">
                            @csrf
                            @method('PUT') <input type="hidden" name="verification_status" value="approved">
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs uppercase transition tracking-wider shadow-xs">
                                Setujui
                            </button>
                        </form>

                        <form id="showVerifyRejectForm" method="POST" class="flex-1">
                            @csrf
                            @method('PUT') <input type="hidden" name="verification_status" value="rejected">
                            <button type="submit" class="w-full py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg font-bold text-xs uppercase transition tracking-wider">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function openCvModal(pdfUrl, teacherName) {
            document.getElementById('cv_modal_title').innerText = 'Milik Pengajar: ' + teacherName;
            document.getElementById('cv_iframe').src = pdfUrl;
            toggleModal('viewCvModal');
        }

        function handleShowModal(button) {
            const profile = JSON.parse(button.getAttribute('data-profile'));
            const user = JSON.parse(button.getAttribute('data-user'));
            openShowModal(profile, user);
        }

        function openShowModal(profile, user) {
            document.getElementById('show_user_name').innerText = user.name;
            document.getElementById('show_user_email').innerText = user.email;
            document.getElementById('show_title').innerText = profile.title || 'Pengajar Mandiri';
            document.getElementById('show_bio').innerText = profile.bio || '-';
            document.getElementById('show_skills').innerText = profile.skills_tags || 'Tidak ada';
            document.getElementById('show_bank_info').innerText = profile.bank_name + ' - ' + profile.bank_account_number;
            document.getElementById('show_bank_owner').innerText = 'A.n: ' + profile.bank_account_name;
            document.getElementById('show_rating').innerText = '★ ' + parseFloat(profile.average_rating || 0).toFixed(1);

            const verifyContainer = document.getElementById('show_verify_container');
            if (profile.verification_status === 'pending') {
                document.getElementById('showVerifyApproveForm').action = `/teachers/${profile.id}/verify`;
                document.getElementById('showVerifyRejectForm').action = `/teachers/${profile.id}/verify`;
                verifyContainer.classList.remove('hidden');
            } else {
                verifyContainer.classList.add('hidden');
            }

            toggleModal('showTeacherModal');
        }
    </script>
@endsection