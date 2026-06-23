import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@components': path.resolve(__dirname, './resources/js/components'),
            '@views': path.resolve(__dirname, './resources/js/views'),
            '@stores': path.resolve(__dirname, './resources/js/stores'),
            '@api': path.resolve(__dirname, './resources/js/api'),
            '@lib': path.resolve(__dirname, './resources/js/lib'),
        },
    },
});