@extends('layout.template')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-2xl font-semibold text-gray-700 sm:text-3xl">Testimoni Beranda</h3>
                <p class="mt-1 text-sm text-gray-500">Kelola kutipan yang tampil di section publik homepage GuruHub.</p>
            </div>
            <button type="button" onclick="toggleModal('addTestimonialModal')"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-indigo-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Testimoni
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Peran</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Kutipan</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Rating</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Urutan</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($testimonials as $item)
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->role_title }}</td>
                                <td class="max-w-xs px-4 py-3 text-gray-600">
                                    <p class="line-clamp-2 italic">"{{ $item->quote }}"</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-amber-500">★ {{ $item->rating }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $item->sort_order }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if ($item->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="openEditTestimonial(@js($item))"
                                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">Edit</button>
                                        <form action="/homepage-testimonials/{{ $item->id }}" method="POST"
                                            onsubmit="return confirm('Hapus testimoni ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500">Belum ada testimoni. Tambahkan dari tombol di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($testimonials->hasPages())
                <div class="border-t border-gray-100 px-4 py-3">{{ $testimonials->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Modal tambah --}}
    <div id="addTestimonialModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
            <h4 class="text-lg font-bold text-gray-800">Tambah Testimoni Beranda</h4>
            <form action="/homepage-testimonials" method="POST" class="mt-4 space-y-3">
                @csrf
                @include('admin.partials.homepage-testimonial-fields')
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="toggleModal('addTestimonialModal')"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600">Batal</button>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal edit --}}
    <div id="editTestimonialModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
            <h4 class="text-lg font-bold text-gray-800">Edit Testimoni Beranda</h4>
            <form id="editTestimonialForm" method="POST" class="mt-4 space-y-3">
                @csrf
                @method('PUT')
                @include('admin.partials.homepage-testimonial-fields', ['prefix' => 'edit_'])
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="toggleModal('editTestimonialModal')"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600">Batal</button>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.toggle('hidden');
            el.classList.toggle('flex');
        }

        function openEditTestimonial(item) {
            document.getElementById('editTestimonialForm').action = '/homepage-testimonials/' + item.id;
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_role_title').value = item.role_title;
            document.getElementById('edit_quote').value = item.quote;
            document.getElementById('edit_rating').value = item.rating;
            document.getElementById('edit_gradient_from').value = item.gradient_from;
            document.getElementById('edit_gradient_to').value = item.gradient_to;
            document.getElementById('edit_sort_order').value = item.sort_order;
            document.getElementById('edit_is_active').checked = !!item.is_active;
            toggleModal('editTestimonialModal');
        }
    </script>
@endsection
