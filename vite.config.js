import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    server: {
        host: '192.168.10.65',
        port: 5173,

        // ✅ THIS FIXES YOUR ERROR
        cors: {
            origin: 'http://192.168.10.65:8000',
            credentials: true,
        },

        hmr: {
            host: '192.168.10.65',
        }
    },
    plugins: [
        laravel({
            input: ['resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
})
