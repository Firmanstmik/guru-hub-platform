@extends('layout.master-app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Pendapatan Guru</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau porsi pendapatan pengajar dan kelola proses pencairan dana dari
                    sistem ke rekening guru.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/earnings" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    @role('admin')
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..."
                            class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @endrole

                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                        <option value="">Semua Status Transfer</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Ditahan)
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Sudah Ditransfer)
                        </option>
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pending (Belum Dicairkan)
                    </p>
                    <h4 class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}
                    </h4>
                </div>
                <div class="p-3 rounded-full bg-amber-50 text-amber-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Paid (Sudah Ditransfer)
                    </p>
                    <h4 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </h4>
                </div>
                <div class="p-3 rounded-full bg-emerald-50 text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            @role('admin')
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Pengajar / Guru</th>
                            @endrole
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas Sumber Dana</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Invoice Asal</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hak Pendapatan
                                ({{ $teacherPercentage * 100 }}%)</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi Tindakan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @forelse($earnings as $payment)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                @role('admin')
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $payment->student_name }}
                                    </td>
                                @endrole

                                <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                    {{ $payment->course_title }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-500">
                                    #INV-{{ $payment->payment_id }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    Rp {{ number_format($payment->amount_earned, 0, ',', '.') }}
                                    <span class="block text-[10px] text-gray-400 font-normal mt-0.5">
                                        (Harga Kelas: Rp {{ number_format($payment->gross_amount, 0, ',', '.') }})
                                    </span>
                                </td>

                                {{-- UPDATE STATUS: Membaca status murni dari tabel teacher_earnings --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($payment->earning_status === 'withdrawn')
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Paid</span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Pending</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium flex justify-end">
                                    @if ($payment->earning_status === 'unpaid')
                                        <span class="text-xs text-amber-600 italic font-medium">
                                            Menunggu Proses Transfer Admin
                                        </span>
                                    @else
                                        <span class="text-xs text-green-600 italic">Selesai diproses
                                            ({{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }})</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? '6' : '5' }}"
                                    class="px-6 py-12 text-center text-gray-500">Belum ada rekaman bagi hasil komisi guru
                                    yang tercatat.</td>
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
