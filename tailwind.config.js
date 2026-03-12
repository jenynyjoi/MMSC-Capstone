import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                'dark-bg': '#0f172a',
                'dark-border': '#1e293b',
                'dark-card': '#1e293b',
                'mainBlue': '#0d4c8f',
                'hoverBlue': '#010694',
            },
            backgroundImage: {
                'glass-gradient': 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)',
            },
            boxShadow: {
                'card': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
            },
            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                fadeUp: 'fadeUp 0.5s ease-out forwards',
            },
        },
    },

    plugins: [forms],
};
