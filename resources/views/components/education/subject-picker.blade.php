@props([
    'levels' => collect(),
    'selected' => [],
    'name' => 'subject_ids',
])

<div {{ $attributes->merge(['class' => 'gh-subject-picker space-y-3 max-h-72 overflow-y-auto rounded-xl border border-gray-100 bg-gray-50/50 p-3']) }}>
    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Pilih jenjang & mapel yang Anda ajarkan</p>

    @foreach ($levels as $level)
        @if ($level->subjects->isNotEmpty())
            <div>
                <p class="mb-2 text-xs font-bold text-indigo-700">{{ $level->icon }} {{ $level->name }}</p>
                <div class="grid grid-cols-1 gap-1.5 sm:grid-cols-2">
                    @foreach ($level->subjects as $subject)
                        <label class="flex cursor-pointer items-start gap-2 rounded-lg border border-transparent bg-white px-2.5 py-2 text-xs hover:border-indigo-100">
                            <input type="checkbox"
                                name="{{ $name }}[]"
                                value="{{ $subject->id }}"
                                class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                @checked(in_array($subject->id, $selected, true))>
                            <span class="leading-snug">
                                <span class="font-semibold text-gray-800">{{ $subject->name }}</span>
                                <span class="block text-[10px] text-gray-400">{{ $subject->category->name ?? '' }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
