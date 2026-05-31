@extends('layout.master-app')
@section('content')
    <div class="space-y-4">
        <h2 class="text-xl text-center font-bold">Semua Kelas</h2>
        <div class="space-y-3">
            @forelse($categories as $category)
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gray-200 transition-all duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div class="flex-1 min-w-0 grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 items-center">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 truncate" title="{{ $category->name }}">
                                    {{ $category->name }}
                                </h3>
                                <span class="text-[10px] font-mono text-gray-400 block tracking-tight truncate mt-0.5">
                                    {{ $category->slug }}
                                </span>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-400 font-medium md:truncate"
                                    title="{{ $category->description }}">
                                    {{ $category->description ?? 'Belum ada deskripsi pelengkap untuk kategori ini.' }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold bg-indigo-50 text-indigo-600">
                                📁 {{ $category->courses_count }} Kelas
                            </span>

                            {{-- <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ $category }})"
                                    class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-700 text-xs font-bold rounded-xl transition-all">
                                    Edit
                                </button>

                                <form action="/categories/{{ $category->id }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-600 text-xs font-bold rounded-xl transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div> --}}
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center space-y-3 shadow-sm">
                    <div
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-xl">
                        📁
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Data Kategori Kosong</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Belum ada data kategori fokus pembelajaran yang
                            terdaftar di dalam platform Guru Hub.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($categories->hasPages())
            <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-sm">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
