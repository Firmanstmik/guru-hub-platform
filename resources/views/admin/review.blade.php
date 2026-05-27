@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Moderasi Ulasan & Rating</h3>
            <p class="text-sm text-gray-500 mt-1">Pantau umpan balik dari murid dan lakukan moderasi terhadap ulasan yang melanggar ketentuan.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <!-- PENGUBAHAN: Menggunakan hardcoded URL langsung pada form index filter -->
            <form action="/reviews" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari isi ulasan / murid..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                
                <select name="rating" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Rating</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>⭐ {{ $i }} Bintang</option>
                    @endfor
                </select>

                <select name="course_id" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Murid</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas & Pengajar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nilai Rating</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Komentar / Ulasan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $review->student->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800 leading-tight">{{ $review->course->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Guru: {{ $review->teacher->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-amber-400 gap-0.5">
                                @for($star = 1; $star <= 5; $star++)
                                    @if($star <= $review->rating)
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endif
                                @endfor
                                <span class="text-xs text-gray-500 ml-1 font-semibold">({{ $review->rating }}/5)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-sm break-words italic">
                            "{{ $review->comment ?? 'Hanya memberikan rating bintang.' }}"
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                            {{ $review->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end mt-1">
                            <!-- PENGUBAHAN: Menggunakan hardcoded URL untuk aksi hapus / take down ulasan -->
                            <form action="/reviews/{{ $review->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen dari sistem? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-md text-xs font-semibold border border-rose-200 transition duration-150">
                                    Hapus / Take Down
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data ulasan atau rating dari murid yang masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection