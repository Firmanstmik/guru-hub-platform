@props([
    'subjects',
    'name' => 'subject_id',
    'id' => null,
    'required' => true,
    'selected' => null,
    'placeholder' => 'Pilih jenjang & mata pelajaran',
])

<select
    name="{{ $name }}"
    @if ($id) id="{{ $id }}" @endif
    @if ($required) required @endif
    {{ $attributes->merge(['class' => 'gh-app-select w-full']) }}
>
    <option value="">{{ $placeholder }}</option>
    @foreach ($subjects->groupBy(fn ($s) => $s->educationLevel?->name ?? 'Lainnya') as $levelName => $levelSubjects)
        <optgroup label="{{ $levelName }}">
            @foreach ($levelSubjects as $subject)
                <option value="{{ $subject->id }}" @selected((string) $selected === (string) $subject->id)>
                    {{ $subject->name }} · {{ $subject->category?->name }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
