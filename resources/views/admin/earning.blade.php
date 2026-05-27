@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Pendapatan & Bagi Hasil Guru</h3>
            <p class="text-sm text-gray-500 mt-1">Pantau porsi pendapatan pengajar dan kelola proses pencairan dana dari sistem ke rekening guru.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <!-- PENGUBAHAN: Menggunakan hardcoded URL langsung pada form index filter -->
            <form action="/earnings" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Ditahan)</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Sudah Ditransfer)</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pending (Belum Dicairkan)</p>
                <h4 class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}</h4>
            </div>
            <div class="p-3 rounded-full bg-amber-50 text-amber-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Paid (Sudah Ditransfer)</p>
                <h4 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
            </div>
            <div class="p-3 rounded-full bg-emerald-50 text-emerald-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Pengajar / Guru</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas Sumber Dana</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Invoice Asal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hak Pendapatan (Bagi Hasil)</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($earnings as $earn)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $earn->teacher->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                            {{ $earn->payment->course->title ?? 'Kelas Terhapus' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-500">
                            {{ $earn->payment->invoice_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                            Rp {{ number_format($earn->amount_earned, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($earn->status === 'paid')
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Paid</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium flex justify-end">
                            @if($earn->status === 'pending')
                                <!-- PENGUBAHAN: Menggunakan hardcoded URL untuk aksi pembaruan status transfer pencairan dana -->
                                <form action="/earnings/{{ $earn->id }}/status" method="POST" onsubmit="return confirm('Apakah Anda sudah mentransfer hak dana bagi hasil ini ke rekening guru terkait secara manual?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1.5 rounded-md shadow-xs transition">
                                        Tandai Sudah Ditransfer
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Selesai diproses ({{ $earn->updated_at->format('d/m/Y') }})</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada rekaman bagi hasil komisi guru yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $earnings->links() }}
        </div>
    </div>
</div>
@endsection