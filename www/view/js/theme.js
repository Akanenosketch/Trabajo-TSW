(function () {
    const body = document.body;
    const storageKey = 'theme';

    function applyTheme(theme) {
        const toggleBtn = document.getElementById('themeToggle');
        if (theme === 'light') {
            body.classList.add('light-theme');
            if (toggleBtn) toggleBtn.textContent = '🌞';
            if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Switch to dark mode');
        } else {
            body.classList.remove('light-theme');
            if (toggleBtn) toggleBtn.textContent = '🌙';
            if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Switch to light mode');
        }
    }

    // Determine initial theme: saved preference -> user preference -> default dark
    let saved = localStorage.getItem(storageKey);
    if (!saved) {
        saved = (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) ? 'light' : 'dark';
    }
    applyTheme(saved);

    // Wait for DOM to load to attach toggle handler if needed
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        btn.addEventListener('click', function () {
            const current = document.body.classList.contains('light-theme') ? 'light' : 'dark';
            const next = current === 'light' ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem(storageKey, next);
        });
    });
})();