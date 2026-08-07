{{-- Professional silver skeleton page loader (slow network / navigation) --}}
<style>
/* Critical first-paint (before Vite CSS) */
#gh-page-loader{position:fixed;inset:0;z-index:99990;display:flex;flex-direction:column;background:linear-gradient(180deg,#f4f5f7,#ebecef 48%,#f7f8fa);transition:opacity .32s ease,visibility .32s ease}
#gh-page-loader.is-hiding{opacity:0;visibility:hidden;pointer-events:none}
#gh-page-loader[hidden]{display:none!important}
.gh-pl-bar{height:2px;background:#d8dce3;overflow:hidden}
.gh-pl-bar>i{display:block;height:100%;width:40%;background:linear-gradient(90deg,#b8bec8,#8b93a1,#c9ced6);animation:ghPlProg 1.1s ease-in-out infinite}
.gh-pl-shell{flex:1;width:min(100%,430px);margin:0 auto;padding:14px 16px 28px;display:flex;flex-direction:column;gap:14px}
.gh-pl-brand{display:flex;align-items:center;gap:10px}
.gh-pl-logo{width:36px;height:36px;border-radius:12px;background:linear-gradient(145deg,#dfe3e9,#c5cad3)}
.gh-pl-sh{position:relative;overflow:hidden;background:#dde1e7;border-radius:10px}
.gh-pl-sh:after{content:"";position:absolute;inset:0;transform:translateX(-100%);background:linear-gradient(90deg,transparent,rgba(255,255,255,.55),transparent);animation:ghPlShim 1.35s ease-in-out infinite}
.gh-pl-hero{height:128px;border-radius:20px;background:linear-gradient(135deg,#d5dae2,#c2c8d2,#dfe3ea);position:relative;overflow:hidden;box-shadow:0 10px 28px rgba(100,116,139,.12)}
.gh-pl-hero:after{content:"";position:absolute;inset:0;background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,.45) 50%,transparent 70%);animation:ghPlShim 1.6s ease-in-out infinite}
.gh-pl-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.gh-pl-tile{aspect-ratio:1;border-radius:18px;background:#e4e7ec;border:1px solid rgba(148,163,184,.25);position:relative;overflow:hidden}
.gh-pl-tile:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);transform:translateX(-100%);animation:ghPlShim 1.4s ease-in-out infinite}
.gh-pl-card{display:flex;align-items:center;gap:12px;padding:12px;border-radius:18px;background:rgba(255,255,255,.72);border:1px solid rgba(148,163,184,.28)}
.gh-pl-av{width:44px;height:44px;border-radius:14px;flex-shrink:0}
.gh-pl-meta{margin-top:auto;display:flex;flex-direction:column;align-items:center;gap:10px;padding-top:8px}
.gh-pl-cap{font:600 11px/1.2 system-ui,sans-serif;letter-spacing:.08em;text-transform:uppercase;color:#8b93a1}
.gh-pl-dock{display:flex;justify-content:space-around;padding:12px 8px;border-radius:20px;background:rgba(255,255,255,.75);border:1px solid rgba(148,163,184,.28)}
.gh-pl-dot{width:28px;height:28px;border-radius:10px}
@keyframes ghPlShim{to{transform:translateX(100%)}}
@keyframes ghPlProg{0%{transform:translateX(-120%)}100%{transform:translateX(320%)}}
@media(min-width:768px){.gh-pl-shell{width:min(100%,720px);padding-top:28px}.gh-pl-grid{grid-template-columns:repeat(6,1fr)}.gh-pl-hero{height:160px}}
</style>

<div id="gh-page-loader" class="gh-page-loader" role="status" aria-live="polite" aria-busy="true" aria-label="Memuat halaman">
    <div class="gh-pl-bar gh-page-loader__topbar" aria-hidden="true"><i class="gh-page-loader__topbar-run"></i></div>

    <div class="gh-pl-shell gh-page-loader__shell">
        <div class="gh-pl-brand gh-page-loader__brand">
            <div class="gh-pl-logo gh-page-loader__logo" aria-hidden="true"></div>
            <div class="gh-page-loader__brand-text" style="flex:1;display:flex;flex-direction:column;gap:6px">
                <div class="gh-pl-sh gh-skel gh-skel--title" style="width:42%;height:12px"></div>
                <div class="gh-pl-sh gh-skel gh-skel--sub" style="width:28%;height:8px"></div>
            </div>
        </div>

        <div class="gh-pl-hero gh-page-loader__hero" aria-hidden="true"></div>

        <div class="gh-pl-grid gh-page-loader__grid" aria-hidden="true">
            @for ($i = 0; $i < 6; $i++)
                <div class="gh-pl-tile gh-page-loader__tile"></div>
            @endfor
        </div>

        <div class="gh-pl-sh" style="width:36%;height:10px;margin-top:2px" aria-hidden="true"></div>

        <div class="gh-page-loader__list" style="display:flex;flex-direction:column;gap:10px" aria-hidden="true">
            @for ($i = 0; $i < 3; $i++)
                <div class="gh-pl-card gh-page-loader__list-card">
                    <div class="gh-pl-sh gh-pl-av gh-page-loader__avatar"></div>
                    <div style="flex:1;display:flex;flex-direction:column;gap:8px;min-width:0">
                        <div class="gh-pl-sh" style="width:68%;height:10px"></div>
                        <div class="gh-pl-sh" style="width:44%;height:8px"></div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="gh-pl-meta gh-page-loader__meta">
            <div class="gh-page-loader__pulse" style="width:10px;height:10px;border-radius:999px;background:#9aa3b2"></div>
            <div class="gh-pl-cap gh-page-loader__caption">Memuat GuruHub</div>
        </div>

        <div class="gh-pl-dock gh-page-loader__dock" aria-hidden="true">
            @for ($i = 0; $i < 5; $i++)
                <div class="gh-pl-sh gh-pl-dot gh-page-loader__dock-item"></div>
            @endfor
        </div>
    </div>
</div>

<script>
(function () {
    if (window.__ghPageLoaderBound) return;
    window.__ghPageLoaderBound = true;

    var MIN_MS = 480;
    var shownAt = Date.now();
    var hideTimer = null;

    function node() { return document.getElementById('gh-page-loader'); }

    function show() {
        var el = node();
        if (!el) return;
        clearTimeout(hideTimer);
        el.hidden = false;
        el.classList.remove('is-hiding');
        el.setAttribute('aria-busy', 'true');
        document.body.classList.add('gh-is-loading');
        shownAt = Date.now();
    }

    function hide() {
        var el = node();
        if (!el || el.hidden) return;
        var wait = Math.max(0, MIN_MS - (Date.now() - shownAt));
        clearTimeout(hideTimer);
        hideTimer = setTimeout(function () {
            el.classList.add('is-hiding');
            el.setAttribute('aria-busy', 'false');
            document.body.classList.remove('gh-is-loading');
            setTimeout(function () {
                if (el.classList.contains('is-hiding')) el.hidden = true;
            }, 340);
        }, wait);
    }

    function sameOriginNav(a) {
        if (!a || a.target === '_blank' || a.hasAttribute('download')) return false;
        if (a.getAttribute('data-no-loader') === 'true') return false;
        var href = a.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) return false;
        try {
            var url = new URL(href, window.location.href);
            return url.origin === window.location.origin;
        } catch (e) { return false; }
    }

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        var a = e.target.closest && e.target.closest('a[href]');
        if (!sameOriginNav(a)) return;
        var url = new URL(a.href, window.location.href);
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;
        show();
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        if (form.getAttribute('data-no-loader') === 'true') return;
        if (form.target === '_blank') return;
        show();
    }, true);

    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hide();
    });

    if (document.readyState === 'complete') {
        hide();
    } else {
        window.addEventListener('load', hide, { once: true });
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(hide, 140);
        });
    }

    window.ghPageLoader = { show: show, hide: hide };
})();
</script>
