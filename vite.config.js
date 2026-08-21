import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    // ✅ Ensure proper resolution of node_modules
    resolve: {
        alias: {
            '~': '/node_modules/',
        }
    },
    // ✅ Optimize dependencies
    optimizeDeps: {
        // include: ['jquery', 'datatables.net', 'datatables.net-dt'],
        include: [
            'jquery',
            'datatables.net',
            'datatables.net-dt',
            'toastr',
            'select2',
            'bootstrap',
            '@ckeditor/ckeditor5-build-classic'
        ],
    },
});
