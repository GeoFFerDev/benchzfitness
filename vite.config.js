import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
           input: [
                'resources/css/app.css', 
                'resources/css/memberPortal.css',
                'resources/css/membership-plan-management.css',
                'resources/js/app.js',  
                'resources/css/member-management.css',
                'resources/css/admin/dashboard/admin-dashboard.css',
                'resources/css/global.css',
                'resources/css/admin/dashboard/admin-membership-plan.css',
                'resources/css/admin/dashboard/admin-member.css',
                'resources/css/admin/dashboard/admin-profile.css',
                
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
