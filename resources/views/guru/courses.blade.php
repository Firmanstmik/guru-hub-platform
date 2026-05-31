@extends('layout.master-app')
@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Kelas / Kursus</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar program pembelajaran aktif, draf materi, dan arsip program.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/courses" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="category_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                        </option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($courses as $course)
                <div
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div class="flex flex-col sm:flex-row items-start gap-4 flex-1 min-w-0">
                            <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : 'https://placehold.co/600x400?text=No+Image' }}"
                                alt="Cover {{ $course->title }}"
                                class="w-full sm:w-28 h-20 sm:h-16 object-cover rounded-lg bg-gray-100 border flex-shrink-0">

                            <div class="space-y-1 w-full min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-sm font-bold text-gray-900 truncate max-w-md"
                                        title="{{ $course->title }}">
                                        {{ $course->title }}
                                    </h4>
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-50 text-indigo-600">
                                        {{ $course->category->name }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 font-medium line-clamp-2 sm:line-clamp-1"
                                    title="{{ $course->description }}">
                                    {{ $course->description }}
                                </p>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-gray-400 pt-1">
                                    @role('admin')
                                        <span class="flex items-center gap-1">
                                            👨‍🏫 <span class="font-medium text-gray-600">{{ $course->teacher->name }}</span>
                                        </span>
                                    @endrole
                                    <span class="flex items-center gap-1">
                                        👥 <span class="font-medium text-gray-600">{{ $course->students_count }}
                                            Siswa</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between lg:justify-end gap-6 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <div class="text-left lg:text-right">
                                <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">Harga
                                    Paket</span>
                                <span class="text-sm font-black text-gray-900">
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                @if ($course->status === 'published')
                                    <span
                                        class="inline-flex px-2.5 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-600 rounded-xl">
                                        Published
                                    </span>
                                @elseif($course->status === 'draft')
                                    <span
                                        class="inline-flex px-2.5 py-1 text-[10px] font-bold bg-amber-50 text-amber-700 rounded-xl">
                                        Draft
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2.5 py-1 text-[10px] font-bold bg-gray-50 text-gray-500 rounded-xl">
                                        Archived
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                        📖
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Kelas Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ada program pembelajaran yang sesuai dengan
                            kriteria filter sistem saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($courses->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
@endsection
