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

    Alpine.data('ghCategoryPicker', (categories = [], studentLevelSlug = null) => ({
        categories,
        studentLevelSlug,
        sheetOpen: false,
        activeSlug: null,

        get current() {
            return this.categories.find((c) => c.slug === this.activeSlug) ?? null;
        },

        levelsForCategory(cat) {
            if (!this.studentLevelSlug) {
                return cat.levels;
            }

            return cat.levels.filter((level) => level.slug === this.studentLevelSlug);
        },

        openCategory(slug) {
            const cat = this.categories.find((c) => c.slug === slug);
            if (!cat) return;

            const levels = this.levelsForCategory(cat);

            if (levels.length === 1) {
                window.location.href = levels[0].url;
                return;
            }

            if (levels.length === 0) {
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

function ghSyncModalBodyState() {
    const openDialog = document.querySelector('[role="dialog"][data-gh-open="true"]');
    if (openDialog) {
        document.body.classList.add('gh-modal-open');
        document.body.style.overflow = 'hidden';
        return;
    }

    document.body.classList.remove('gh-modal-open');
    document.body.style.overflow = '';
}

window.openModal = function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    document.querySelectorAll('[role="dialog"][data-gh-open="true"]').forEach((openDialog) => {
        if (openDialog === modal) return;
        openDialog.classList.add('hidden');
        openDialog.setAttribute('hidden', '');
        openDialog.removeAttribute('data-gh-open');
    });

    modal.classList.remove('hidden');
    modal.removeAttribute('hidden');
    modal.setAttribute('data-gh-open', 'true');
    ghSyncModalBodyState();
};

window.closeModal = function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('hidden');
    modal.setAttribute('hidden', '');
    modal.removeAttribute('data-gh-open');
    ghSyncModalBodyState();
};

window.toggleModal = function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const isHidden = modal.classList.contains('hidden') || modal.hasAttribute('hidden');
    if (isHidden) {
        window.openModal(modalId);
    } else {
        window.closeModal(modalId);
    }
};

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    const openDialog = document.querySelector('[role="dialog"][data-gh-open="true"]');
    if (openDialog?.id) window.closeModal(openDialog.id);
});

window.Alpine = Alpine;
Alpine.start();
