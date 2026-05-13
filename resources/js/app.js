import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const THEME_STORAGE_KEY = 'theme';

window.setTheme = function (mode) {
    const enabled = mode === 'dark';
    document.documentElement.classList.toggle('dark', enabled);
    document.documentElement.style.colorScheme = enabled ? 'dark' : 'light';
    localStorage.setItem(THEME_STORAGE_KEY, enabled ? 'dark' : 'light');
};

window.getPreferredTheme = function () {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    if (stored === 'light' || stored === 'dark') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

window.initializeTheme = function () {
    const theme = window.getPreferredTheme();
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.style.colorScheme = theme === 'dark' ? 'dark' : 'light';
};

window.updateThemeToggleUI = function () {
    const isDark = document.documentElement.classList.contains('dark');
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        button.querySelector('[data-theme-light]')?.classList.toggle('hidden', isDark);
        button.querySelector('[data-theme-dark]')?.classList.toggle('hidden', !isDark);
    });
};

window.initializeThemeToggles = function () {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const newTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            window.setTheme(newTheme);
            window.updateThemeToggleUI();
        });
    });
};

window.initializeTheme();

const prefersColorScheme = window.matchMedia('(prefers-color-scheme: dark)');
if (typeof prefersColorScheme.addEventListener === 'function') {
    prefersColorScheme.addEventListener('change', (event) => {
        if (!localStorage.getItem(THEME_STORAGE_KEY)) {
            window.setTheme(event.matches ? 'dark' : 'light');
            window.updateThemeToggleUI();
        }
    });
} else if (typeof prefersColorScheme.addListener === 'function') {
    prefersColorScheme.addListener((event) => {
        if (!localStorage.getItem(THEME_STORAGE_KEY)) {
            window.setTheme(event.matches ? 'dark' : 'light');
            window.updateThemeToggleUI();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    window.initializeThemeToggles();
    window.updateThemeToggleUI();

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-dropdown-trigger]');
        const dropdown = event.target.closest('[data-dropdown]');

        document.querySelectorAll('[data-dropdown]').forEach((container) => {
            const menu = container.querySelector('[data-dropdown-menu]');
            const button = container.querySelector('[data-dropdown-trigger]');
            const isCurrent = dropdown === container;

            if (!menu || !button) {
                return;
            }

            if (isCurrent && trigger) {
                const isOpen = !menu.classList.contains('hidden');
                menu.classList.toggle('hidden', isOpen);
                button.setAttribute('aria-expanded', String(!isOpen));
                return;
            }

            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        });
    });

    window.addEventListener('scroll-to-form', () => {
        const formSection = document.getElementById('expenseForm');
        if (formSection) {
            formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
