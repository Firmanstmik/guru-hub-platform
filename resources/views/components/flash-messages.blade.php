@if (session('success'))
    <div role="status" class="gh-alert gh-alert-success auto-dismiss-alert">
        <div class="gh-alert-icon gh-alert-icon-success">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div role="alert" class="gh-alert gh-alert-danger auto-dismiss-alert">
        <div class="gh-alert-icon gh-alert-icon-danger">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="gh-alert gh-alert-danger auto-dismiss-alert">
        <div class="mb-2 flex items-center gap-2 font-semibold text-danger-800">
            <svg class="h-4 w-4 shrink-0 text-danger-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span>Periksa kembali formulir Anda</span>
        </div>
        <ul class="list-inside list-disc space-y-1 text-danger-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
