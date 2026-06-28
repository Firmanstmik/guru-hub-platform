@extends('layout.master-app')

@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Pendapatan Guru" subtitle="Pantau bagi hasil dan status pencairan dana." />

            <form action="/earnings" method="GET" class="gh-app-filter-bar">
                @role('admin')
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="gh-app-input flex-1">
                @endrole
                <select name="status" onchange="this.form.submit()" class="gh-app-select flex-1">
                    <option value="">Semua Status Transfer</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Ditahan)</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Sudah Ditransfer)</option>
                </select>
            </form>

            <div class="gh-app-stat-grid">
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value text-amber-600" style="background:none;color:#d97706;-webkit-text-fill-color:#d97706">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
                    <p class="gh-app-stat-label">Pending</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value text-emerald-600" style="background:none;color:#059669;-webkit-text-fill-color:#059669">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                    <p class="gh-app-stat-label">Sudah Ditransfer</p>
                </div>
            </div>

            <div class="gh-app-list">
                @forelse($earnings as $payment)
                    <div class="gh-app-payment-card">
                        @role('admin')
                            <p class="gh-app-caption">Guru: <span class="gh-app-subheading">{{ $payment->student_name }}</span></p>
                        @endrole
                        <h3 class="gh-app-subheading mt-1">{{ $payment->course_title }}</h3>
                        <p class="gh-app-caption font-mono">#INV-{{ $payment->payment_id }}</p>
                        <p class="gh-app-stat-value text-[14px] mt-2" style="background:none;color:#06122E;-webkit-text-fill-color:#06122E">
                            Rp {{ number_format($payment->amount_earned, 0, ',', '.') }}
                        </p>
                        <p class="gh-app-caption">Harga kelas: Rp {{ number_format($payment->gross_amount, 0, ',', '.') }} ({{ $teacherPercentage * 100 }}%)</p>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                            @if ($payment->earning_status === 'withdrawn')
                                <x-app.badge variant="success">Paid</x-app.badge>
                                <span class="gh-app-caption">Selesai ({{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }})</span>
                            @else
                                <x-app.badge variant="warning">Pending</x-app.badge>
                                <span class="gh-app-caption italic">Menunggu transfer admin</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <x-app.empty-state icon="circle-dollar-sign" title="Belum ada pendapatan" description="Rekaman bagi hasil komisi guru belum tercatat." />
                @endforelse
            </div>

            <div class="gh-app-card">{{ $earnings->links() }}</div>
        </div>
    </div>
@endsection
