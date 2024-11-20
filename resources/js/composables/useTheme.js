import { onMounted, ref } from 'vue';

export function useTheme() {
    const theme = ref('light'); // Default theme

    const setTheme = (newTheme) => {
        theme.value = newTheme;
        const root = document.documentElement;

        if (newTheme === 'dark') {
            root.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            root.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    const toggleTheme = () => {
        setTheme(theme.value === 'dark' ? 'light' : 'dark');
    };

    onMounted(() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else {
            // Auto-detect system preference if no saved theme exists
            const prefersDark = window.matchMedia(
                '(prefers-color-scheme: dark)',
            ).matches;
            setTheme(prefersDark ? 'dark' : 'light');
        }
    });

    return { theme, setTheme, toggleTheme };
}
