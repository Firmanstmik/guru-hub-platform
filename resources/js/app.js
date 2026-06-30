import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('ghReveal', () => ({
        visible: false,
        init() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.visible = true;
                return;
            }

            const observer = new IntersectionObserver(
                ([entry]) => {
                    if (entry.isIntersecting) {
                        this.visible = true;
                        observer.unobserve(this.$el);
                    }
                },
                { threshold: 0.12, rootMargin: '0px 0px -48px 0px' },
            );

            observer.observe(this.$el);
        },
    }));

    Alpine.data('ghCategoryPicker', (categories = []) => ({
        categories,
        sheetOpen: false,
        activeSlug: null,

        get current() {
            return this.categories.find((c) => c.slug === this.activeSlug) ?? null;
        },

        openCategory(slug) {
            const cat = this.categories.find((c) => c.slug === slug);
            if (!cat) return;

            if (cat.levels.length === 1) {
                window.location.href = cat.levels[0].url;
                return;
            }

            if (cat.levels.length === 0) {
                window.location.href = cat.browse_url;
                return;
            }

            this.activeSlug = slug;
            this.sheetOpen = true;
            document.body.classList.add('gh-sheet-open');
        },

        closeSheet() {
            this.sheetOpen = false;
            this.activeSlug = null;
            document.body.classList.remove('gh-sheet-open');
        },

        go(url) {
            window.location.href = url;
        },

        levelBadge(level) {
            const map = {
                sd: 'SD',
                smp: 'SMP',
                'sma-smk': 'SMA',
                'persiapan-ujian': 'Ujian',
                'minat-bakat': 'Bakat',
            };
            return map[level.slug] ?? level.name.slice(0, 3).toUpperCase();
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();
