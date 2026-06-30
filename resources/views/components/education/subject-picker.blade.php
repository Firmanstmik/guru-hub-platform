@props([
    'levels' => collect(),
    'selected' => [],
    'name' => 'subject_ids',
])

@php
    use Illuminate\Support\Str;

    $selectedIds = array_map('intval', $selected);
    $totalSubjects = $levels->sum(fn ($level) => $level->subjects->count());
    $defaultOpen = $levels
        ->filter(fn ($level) => $level->subjects->isNotEmpty())
        ->take(2)
        ->pluck('id')
        ->values()
        ->all();

    $levelSearchIndex = $levels
        ->filter(fn ($level) => $level->subjects->isNotEmpty())
        ->mapWithKeys(fn ($level) => [
            $level->id => $level->subjects
                ->map(fn ($subject) => Str::lower($subject->name . ' ' . ($subject->category?->name ?? '')))
                ->values()
                ->all(),
        ])
        ->all();
@endphp

<div
    {{ $attributes->class(['gh-subject-picker']) }}
    x-data="{
        query: '',
        openLevels: @js($defaultOpen),
        selectedCount: {{ count($selectedIds) }},
        levelIndex: @js($levelSearchIndex),
        toggleLevel(id) {
            if (this.openLevels.includes(id)) {
                this.openLevels = this.openLevels.filter(levelId => levelId !== id);
            } else {
                this.openLevels.push(id);
            }
        },
        isOpen(id) {
            return this.openLevels.includes(id);
        },
        levelMatches(levelId) {
            const q = this.query.trim().toLowerCase();
            if (!q) return true;
            const items = this.levelIndex[levelId] || [];
            return items.some(text => text.includes(q));
        },
        subjectMatches(name, category) {
            const q = this.query.trim().toLowerCase();
            if (!q) return true;
            return (name + ' ' + (category || '')).toLowerCase().includes(q);
        },
        refreshCount() {
            this.selectedCount = $el.querySelectorAll('.gh-subject-chip-input:checked').length;
        }
    }"
    @change="refreshCount()"
>
    <div class="gh-subject-picker-toolbar">
        <div class="gh-subject-picker-search-wrap">
            <x-ui.lucide name="search" class="gh-subject-picker-search-icon" />
            <input
                type="search"
                x-model="query"
                placeholder="Cari mapel..."
                class="gh-subject-picker-search"
                autocomplete="off"
            >
        </div>
        <span class="gh-subject-picker-count" x-text="selectedCount + ' mapel dipilih'"></span>
    </div>

    <p class="gh-subject-picker-hint">Pilih jenjang, lalu tap mapel yang Anda ampu. Ini menentukan kelas yang bisa dibuat.</p>

    <div class="gh-subject-picker-levels">
        @foreach ($levels as $level)
            @if ($level->subjects->isNotEmpty())
                @php
                    $levelSelected = $level->subjects->whereIn('id', $selectedIds)->count();
                @endphp
                <section class="gh-subject-level" x-show="levelMatches({{ $level->id }})" x-cloak>
                    <button
                        type="button"
                        class="gh-subject-level-trigger"
                        @click="toggleLevel({{ $level->id }})"
                        :aria-expanded="isOpen({{ $level->id }})"
                    >
                        <span class="gh-subject-level-icon">{{ $level->icon }}</span>
                        <span class="gh-subject-level-copy">
                            <span class="gh-subject-level-name">{{ $level->name }}</span>
                            <span class="gh-subject-level-meta">{{ $level->subjects->count() }} mapel tersedia</span>
                        </span>
                        @if ($levelSelected > 0)
                            <span class="gh-subject-level-badge">{{ $levelSelected }}</span>
                        @endif
                        <span class="gh-subject-level-chevron" :class="{ 'gh-subject-level-chevron--open': isOpen({{ $level->id }}) }">
                            <x-ui.lucide name="chevron-down" class="h-4 w-4" />
                        </span>
                    </button>

                    <div
                        class="gh-subject-level-panel"
                        x-show="isOpen({{ $level->id }})"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                    >
                        <div class="gh-subject-chip-grid">
                            @foreach ($level->subjects as $subject)
                                @php
                                    $categoryName = $subject->category?->name;
                                    $showCategory = filled($categoryName)
                                        && strcasecmp($categoryName, $subject->name) !== 0;
                                @endphp
                                <label
                                    class="gh-subject-chip"
                                    x-show="subjectMatches(@js($subject->name), @js($categoryName ?? ''))"
                                >
                                    <input
                                        type="checkbox"
                                        name="{{ $name }}[]"
                                        value="{{ $subject->id }}"
                                        class="gh-subject-chip-input"
                                        @checked(in_array($subject->id, $selectedIds, true))
                                    >
                                    <span class="gh-subject-chip-check" aria-hidden="true">
                                        <x-ui.lucide name="shield-check" class="h-3.5 w-3.5" />
                                    </span>
                                    <span class="gh-subject-chip-body">
                                        <span class="gh-subject-chip-title">{{ $subject->name }}</span>
                                        @if ($showCategory)
                                            <span class="gh-subject-chip-meta">{{ $categoryName }}</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    </div>

    @if ($totalSubjects === 0)
        <p class="gh-subject-picker-empty">Belum ada mapel tersedia. Hubungi admin untuk mengaktifkan katalog jenjang.</p>
    @endif
</div>
