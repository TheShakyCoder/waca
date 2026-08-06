import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

const port = 5173;

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: process.env.DDEV_PRIMARY_URL
        ? {
            cors: true,
            host: '0.0.0.0',
            port: port,
            strictPort: true,
            origin: `${process.env.DDEV_PRIMARY_URL}:${port}`,
            hmr: {
                host: process.env.DDEV_HOSTNAME,
                protocol: 'wss',
            },
        }
        : {},
});
