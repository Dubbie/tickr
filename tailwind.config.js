import forms from '@tailwindcss/forms';
import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Zilla Slab', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    darkMode: 'selector',

    plugins: [forms],

    safelist: [
        'text-yellow-300',
        'text-blue-400',
        'max-w-xs',
        'max-w-sm',
        'max-w-lg',
        'max-w-xl',
        'max-w-2xl',
        'max-w-3xl',
        'max-w-4xl',
    ],
};
