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
});

window.Alpine = Alpine;
Alpine.start();
