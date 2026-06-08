@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelas Siswa</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau siswa aktif, kelola status kelulusan, dan berikan akses modul
                    kelas secara manual.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/course-students" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama murid..."
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">

                    <select name="course_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Belajar)
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Lulus)
                        </option>
                    </select>
                </form>

                <button onclick="openCreateModal()"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Daftarkan Murid
                </button>
            </div>
        </div>

        <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Murid</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Program Kursus / Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu Bergabung</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status Belajar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi Kontrol</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($enrollments as $enroll)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $enroll->student->name }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $enroll->student->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium max-w-xs truncate">
                                {{ $enroll->course->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                {{ $enroll->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($enroll->status === 'active')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">Active</span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">Completed
                                        🎓</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex justify-end items-center gap-3">
                                    <button
                                        onclick="openEditModal({{ $enroll->id }}, '{{ $enroll->student->name }}', '{{ $enroll->course->title }}', '{{ $enroll->status }}')"
                                        class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        Ubah Status
                                    </button>

                                    <form action="/course-students/{{ $enroll->id }}" method="POST" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan murid ini dari kelas? Akses materi mereka akan dicabut.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-400 hover:text-rose-600 font-semibold">Keluarkan</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Tidak ada rekam jejak siswa yang
                                terdaftar di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $enrollments->links() }}
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" onclick="closeModal('createModal')">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full z-10">
                <form action="/course-students" method="POST" class="p-6">
                    @csrf
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftarkan Murid Secara Manual</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pilih
                                Murid</label>
                            <select name="student_id" required
                                class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500">
                                <option value="">-- Pilih Akun Murid --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pilih
                                Program Kelas</label>
                            <select name="course_id" required
                                class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status
                                Awal Belajar</label>
                            <select name="status" required
                                class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500">
                                <option value="active" selected>Active (Masih Belajar)</option>
                                <option value="completed">Completed (Selesai & Buka Sertifikat)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" onclick="closeModal('createModal')"
                            class="bg-white border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs">Daftarkan
                            Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" onclick="closeModal('editModal')">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full z-10">
                <form id="editForm" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Perbarui Status Pembelajaran</h3>
                    <p class="text-xxs text-gray-400 mb-4 leading-tight">Mengubah status menjadi <span
                            class="font-bold text-emerald-600">Completed</span> akan otomatis merilis hak sertifikat
                        kelulusan kelas bagi murid.</p>

                    <div class="space-y-3 mb-5 bg-gray-50 p-3 rounded-lg border border-gray-100 text-xs">
                        <div><span class="text-gray-400">Nama Siswa:</span> <span id="editStudentName"
                                class="font-bold text-gray-800"></span></div>
                        <div><span class="text-gray-400">Nama Kelas:</span> <span id="editCourseTitle"
                                class="font-medium text-gray-800"></span></div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status
                            Partisipasi</label>
                        <select name="status" id="editStatusSelect" required
                            class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500">
                            <option value="active">Active (Sedang Menempuh Materi)</option>
                            <option value="completed">Completed (Lulus / Selesai Kelas)</option>
                        </select>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" onclick="closeModal('editModal')"
                            class="bg-white border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit"
                            class="bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function openEditModal(id, studentName, courseTitle, currentStatus) {
            document.getElementById('editStudentName').innerText = studentName;
            document.getElementById('editCourseTitle').innerText = courseTitle;
            document.getElementById('editStatusSelect').value = currentStatus;

            // PENGUBAHAN: Set action URL langsung tanpa template blade route name
            const form = document.getElementById('editForm');
            form.action = '/course-students/' + id;

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection
