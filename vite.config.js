import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/homepage.css",
                "resources/css/login.css",
                "resources/css/register.css",
                "resources/css/room.css",
                "resources/css/about.css",
                "resources/css/faq.css",
                "resources/css/profile.css",
                "resources/css/admin.css",
                "resources/css/owner.css",
                "resources/css/auth.css",
                "resources/css/payment.css",
            ],
            refresh: true,
        }),
    ],
});
