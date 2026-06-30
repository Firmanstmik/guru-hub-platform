@php $p = $prefix ?? ''; @endphp

<div>
    <label for="{{ $p }}name" class="mb-1 block text-xs font-semibold text-gray-600">Nama</label>
    <input type="text" name="name" id="{{ $p }}name" required
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
</div>
<div>
    <label for="{{ $p }}role_title" class="mb-1 block text-xs font-semibold text-gray-600">Peran / Jabatan</label>
    <input type="text" name="role_title" id="{{ $p }}role_title" required placeholder="Siswa SMA · Matematika"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
</div>
<div>
    <label for="{{ $p }}quote" class="mb-1 block text-xs font-semibold text-gray-600">Kutipan</label>
    <textarea name="quote" id="{{ $p }}quote" rows="3" required
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label for="{{ $p }}rating" class="mb-1 block text-xs font-semibold text-gray-600">Rating (1-5)</label>
        <select name="rating" id="{{ $p }}rating" required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500">
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} bintang</option>
            @endfor
        </select>
    </div>
    <div>
        <label for="{{ $p }}sort_order" class="mb-1 block text-xs font-semibold text-gray-600">Urutan tampil</label>
        <input type="number" name="sort_order" id="{{ $p }}sort_order" min="0" value="0"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500">
    </div>
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label for="{{ $p }}gradient_from" class="mb-1 block text-xs font-semibold text-gray-600">Warna avatar (dari)</label>
        <input type="text" name="gradient_from" id="{{ $p }}gradient_from" value="#14B8A6"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500">
    </div>
    <div>
        <label for="{{ $p }}gradient_to" class="mb-1 block text-xs font-semibold text-gray-600">Warna avatar (ke)</label>
        <input type="text" name="gradient_to" id="{{ $p }}gradient_to" value="#0E7490"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500">
    </div>
</div>
<div class="flex items-center gap-2">
    <input type="checkbox" name="is_active" id="{{ $p }}is_active" value="1" checked
        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
    <label for="{{ $p }}is_active" class="text-sm text-gray-700">Tampilkan di beranda publik</label>
</div>
