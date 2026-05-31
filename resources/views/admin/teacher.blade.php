@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Verifikasi Profil Pengajar</h3>
                <p class="text-sm text-gray-500 mt-1">Validasi berkas CV, data perbankan, dan kompetensi keahlian calon guru
                    Guru Hub.</p>
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
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Butuh
                            Review)</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Aktif)
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)
                        </option>
                    </select>
                </form>

                <button onclick="toggleModal('addTeacherModal')"
                    class="inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow transition duration-200 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengajar
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & Gelar
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keahlian &
                                Tag</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen CV
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi
                                Bank</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                Tindakan</th>
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
                                                <span
                                                    class="bg-gray-100 text-gray-700 text-[10px] px-2 py-0.5 rounded font-mono font-medium">{{ trim($tag) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak ada tag</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($profile->cv_file)
                                        <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs text-indigo-600 font-semibold hover:underline bg-indigo-50/80 px-2.5 py-1.5 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z" />
                                            </svg>
                                            Lihat Berkas CV
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum diunggah</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <div class="font-bold text-gray-800">{{ $profile->bank_name }}</div>
                                    <div class="font-mono text-gray-600 font-medium my-0.5">
                                        {{ $profile->bank_account_number }}</div>
                                    <div class="text-[10px] text-gray-400 truncate max-w-[150px]">A.n:
                                        {{ $profile->bank_account_name }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="flex items-center gap-1 text-amber-500 font-bold bg-amber-50 px-2 py-1 rounded-md w-max">
                                        <span class="text-sm">★</span>
                                        <span class="text-xs">{{ number_format($profile->average_rating, 1) }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($profile->verification_status === 'approved')
                                        <span
                                            class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-full">Approved</span>
                                    @elseif($profile->verification_status === 'pending')
                                        <span
                                            class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-bold bg-rose-100 text-rose-800 rounded-full">Rejected</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <button data-profile='@json($profile)'
                                            data-user='@json($profile->user)' onclick="handleShowModal(this)"
                                            class="text-blue-600 hover:text-blue-900">
                                            Show
                                        </button>

                                        <button data-profile='@json($profile)'
                                            data-user='@json($profile->user)' onclick="handleEditModal(this)"
                                            class="text-amber-600 hover:text-amber-900">
                                            Edit
                                        </button>

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
                                <td colspan="7" class="px-6 py-16 text-center text-gray-400">Tidak ada pengajuan berkas
                                    pengajar ditemukan.</td>
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

    <div id="addTeacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form action="/teachers" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-6 py-5 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 pb-2 border-b">Tambah Profil Pengajar</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih User
                                    Terdaftar</label>
                                <select name="user_id" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Gelar /
                                    Bidang</label>
                                <input type="text" name="title" required
                                    placeholder="Contoh: Senior Android Developer"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Keahlian (Pisahkan
                                dengan koma)</label>
                            <input type="text" name="skills_tags" required placeholder="Kotlin, Java, MVVM"
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Bio Singkat</label>
                            <textarea name="bio" rows="3" placeholder="Tulis deskripsi ringkas latar belakang pengajar..."
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama Bank</label>
                                <input type="text" name="bank_name" required placeholder="BCA"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nomor
                                    Rekening</label>
                                <input type="text" name="bank_account_number" required placeholder="0123456789"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama
                                    Pemilik</label>
                                <input type="text" name="bank_account_name" required
                                    placeholder="Sesuai Buku Tabungan"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Lampiran CV
                                    (PDF)</label>
                                <input type="file" name="cv_file"
                                    class="w-full text-sm text-gray-500 border border-gray-200 rounded-lg p-1">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Awal</label>
                                <select name="verification_status" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">Simpan
                            Data</button>
                        <button type="button" onclick="toggleModal('addTeacherModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="showTeacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 py-5 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <h3 class="text-lg font-bold text-gray-900">Detail Berkas Pengajar</h3>
                        <button onclick="toggleModal('showTeacherModal')"
                            class="text-gray-400 hover:text-gray-600">✕</button>
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
                            <p id="show_skills"
                                class="font-mono text-xs text-indigo-700 bg-indigo-50 px-2 py-1 rounded inline-block mt-1">
                            </p>
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

                    <div id="show_verify_container" class="pt-2 flex gap-2 hidden">
                        <form id="showVerifyApproveForm" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="verification_status" value="approved">
                            <button type="submit"
                                class="w-full py-2 bg-emerald-600 text-white rounded-lg font-bold text-xs uppercase transition tracking-wider">Setujui</button>
                        </form>
                        <form id="showVerifyRejectForm" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="verification_status" value="rejected">
                            <button type="submit"
                                class="w-full py-2 bg-rose-100 text-rose-700 rounded-lg font-bold text-xs uppercase transition tracking-wider">Tolak</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editTeacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 py-5 space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Ubah Profil Pengajar</h3>
                            <p id="edit_user_label" class="text-xs text-gray-500 mt-0.5"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Gelar / Kompetensi
                                Utama</label>
                            <input type="text" id="edit_title" name="title" required
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Keahlian (Pisahkan
                                dengan koma)</label>
                            <input type="text" id="edit_skills_tags" name="skills_tags" required
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Bio Singkat</label>
                            <textarea id="edit_bio" name="bio" rows="3"
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama Bank</label>
                                <input type="text" id="edit_bank_name" name="bank_name" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nomor
                                    Rekening</label>
                                <input type="text" id="edit_bank_account_number" name="bank_account_number" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama
                                    Pemilik</label>
                                <input type="text" id="edit_bank_account_name" name="bank_account_name" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Ganti CV (Kosongkan
                                    jika tidak diubah)</label>
                                <input type="file" name="cv_file"
                                    class="w-full text-sm text-gray-500 border border-gray-200 rounded-lg p-1">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status
                                    Verifikasi</label>
                                <select id="edit_verification_status" name="verification_status" required
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-indigo-500">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">Simpan
                            Perubahan</button>
                        <button type="button" onclick="toggleModal('editTeacherModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi umum buka-tutup modal berbasis JavaScript murni (Sama dengan referensi)
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function handleShowModal(button) {
            const profile = JSON.parse(button.getAttribute('data-profile'));
            const user = JSON.parse(button.getAttribute('data-user'));

            // Panggil fungsi openShowModal bawaan Anda sebelumnya
            openShowModal(profile, user);
        }

        // Fungsi perantara untuk mengurai (parse) data dari tombol Edit
        function handleEditModal(button) {
            const profile = JSON.parse(button.getAttribute('data-profile'));
            const user = JSON.parse(button.getAttribute('data-user'));

            // Panggil fungsi openEditModal bawaan Anda sebelumnya
            openEditModal(profile, user);
        }
        // Fungsi pratinjau data (Show)
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

        // Fungsi lempar data ke dalam form edit (Edit)
        function openEditModal(profile, user) {
            document.getElementById('edit_user_label').innerText = 'User Terkait: ' + user.name;
            document.getElementById('edit_title').value = profile.title || '';
            document.getElementById('edit_skills_tags').value = profile.skills_tags || '';
            document.getElementById('edit_bio').value = profile.bio || '';
            document.getElementById('edit_bank_name').value = profile.bank_name || '';
            document.getElementById('edit_bank_account_number').value = profile.bank_account_number || '';
            document.getElementById('edit_bank_account_name').value = profile.bank_account_name || '';
            document.getElementById('edit_verification_status').value = profile.verification_status;

            const form = document.getElementById('editForm');
            form.action = `/teachers/${profile.id}`;

            toggleModal('editTeacherModal');
        }
    </script>
@endsection
