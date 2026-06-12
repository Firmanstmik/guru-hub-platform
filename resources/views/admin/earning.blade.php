@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">
                    @role('admin')
                        Manajemen Pendapatan & Bagi Hasil
                    @else
                        Pendapatan & Bagi Hasil Anda
                    @endrole
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    @role('admin')
                        Pantau porsi komisi pengajar, lacak keuntungan platform, dan kelola konfirmasi transfer pencairan dana
                        guru.
                    @else
                        Pantau porsi pendapatan Anda dari program kelas premium dan lacak proses pencairan dana dari sistem.
                    @endrole
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/earnings" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    @role('admin')
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..."
                            class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 w-full sm:w-48">
                    @endrole

                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 w-full sm:w-48">
                        <option value="">Semua Status Transfer</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                            Pending (Ditahan)
                        </option>
                        <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>
                            Paid (Sudah Ditransfer)
                        </option>
                    </select>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm font-semibold text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Grid Ringkasan Statistik Dinamis -->
        <div
            class="grid grid-cols-1 {{ auth()->user()->hasRole('admin') ? 'md:grid-cols-3' : 'sm:grid-cols-2' }} gap-5 mb-8">
            <!-- Card Pending -->
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        @role('admin')
                            Total Pending Guru
                        @else
                            Pending (Belum Dicairkan)
                        @endrole
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

            <!-- Card Paid -->
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        @role('admin')
                            Total Paid Guru
                        @else
                            Paid (Sudah Ditransfer)
                        @endrole
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

            <!-- BONUS KHUSUS ADMIN: Keuntungan Kas Masuk Platform (30%) -->
            @role('admin')
                @php
                    // Menghitung estimasi porsi keuntungan sistem platform dari total kelas yang sukses
                    $platformPercentage = 1 - $teacherPercentage;
                    // Rumus: Jika totalPaid mencerminkan porsi guru (misal 70%), total transaksi asal adalah totalPaid / 0.7
                    $totalGrossPaid = $teacherPercentage > 0 ? $totalPaid / $teacherPercentage : 0;
                    $platformEarnings = $totalGrossPaid * $platformPercentage;
                @endphp
                <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendapatan Bersih Sistem
                            ({{ $platformPercentage * 100 }}%)</p>
                        <h4 class="text-2xl font-bold text-indigo-600 mt-1">Rp
                            {{ number_format($platformEarnings, 0, ',', '.') }}</h4>
                    </div>
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            @endrole
        </div>

        <!-- Tabel Rekaman Bagi Hasil -->

        <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        @role('admin')
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Pengajar / Guru</th>
                        @endrole
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas Sumber Dana</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Invoice Asal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">
                            @role('admin')
                                Rincian Pembagian Hasil
                            @else
                                Hak Pendapatan Anda ({{ $teacherPercentage * 100 }}%)
                            @endrole
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status Transfer</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($earnings as $payment)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            @role('admin')
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $payment->teacher_name }}
                                </td>
                            @endrole
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ $payment->course_title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-500">
                                {{ $payment->invoice_number ?? '-' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                @role('admin')
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="px-1.5 py-0.2 bg-gray-100 text-gray-600 rounded text-[10px] font-semibold font-mono">Guru
                                                ({{ $teacherPercentage * 100 }}%)</span>
                                            <span class="font-bold text-gray-950">Rp
                                                {{ number_format($payment->amount_earned, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="px-1.5 py-0.2 bg-indigo-50 text-indigo-600 rounded text-[10px] font-semibold font-mono">Sistem
                                                ({{ (1 - $teacherPercentage) * 100 }}%)</span>
                                            <span class="font-medium text-indigo-600">Rp
                                                {{ number_format($payment->gross_amount * (1 - $teacherPercentage), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($payment->amount_earned, 0, ',', '.') }}
                                    </span>
                                @endrole
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($payment->earning_status === 'withdrawn')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Paid</span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium flex justify-end">
                                @if ($payment->earning_status === 'unpaid')
                                    @role('admin')
                                        <form action="/earnings/{{ $payment->payment_id }}/status" method="POST"
                                            onsubmit="return confirm('Apakah Anda sudah mentransfer hak dana bagi hasil ini ke rekening guru terkait secara manual?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="withdrawn">
                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1.5 rounded-md shadow-xs transition-all duration-200">
                                                Tandai Sudah Ditransfer
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-amber-600 italic font-medium flex items-center gap-1">
                                            <span>⏳</span> Menunggu Proses Transfer Admin
                                        </span>
                                    @endrole
                                @else
                                    <span class="text-xs text-gray-400 italic">
                                        Selesai diproses
                                        ({{ \Carbon\Carbon::parse($payment->updated_at)->format('d/m/Y') }})
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('admin') ? '6' : '5' }}"
                                class="px-6 py-12 text-center text-gray-500">
                                Belum ada rekaman bagi hasil komisi guru yang tercatat dalam sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $earnings->links() }}
        </div>
    </div>
@endsection
