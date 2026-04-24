import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/welcome.js',
                'resources/js/dashboard.js',

                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/welcome.css',

            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            external: ['fsevents'],
        },
    },
});
