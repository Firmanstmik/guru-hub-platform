@props(['course', 'selected' => null])

<option value="{{ $course->id }}" @selected((string) $selected === (string) $course->id)>
    {{ $course->catalogLabel() }}
</option>
