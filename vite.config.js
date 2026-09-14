import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/sidebar.css',
                'resources/css/pop.css',
                'resources/css/add-pop.css',
                'resources/css/card.css',
                'resources/css/rectifier-detail.css',
                'resources/css/rectifier-form.css',
                'resources/css/kwh-create.css',
                'resources/css/kwh-detail.css',
                'resources/css/rma.css',
                'resources/css/rma-awal.css',
                'resources/css/users.css',
                'resources/css/access.css',
                'resources/css/manajemen-role.css',
                'resources/css/battery-card.css',
                'resources/css/battery-create.css',
                'resources/css/battery-detail.css',
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/reset.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
