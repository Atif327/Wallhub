import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/style.css',
                'resources/css/homepage.css',
                'resources/css/footer.css',
                'resources/css/categories.css',
                'resources/js/script.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
