import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
import { fileURLToPath, URL } from 'node:url'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    port: 5173,
    proxy: {
      // En desarrollo, las peticiones a /api van al backend Laravel
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      // Imágenes subidas (Storage público) también se sirven desde Laravel en dev
      '/storage': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
  build: {
    // En producción, el build se sirve desde Laravel (backend/public).
    // emptyOutDir en false para NO borrar los archivos propios de Laravel
    // (index.php, .htaccess, robots.txt). Los assets usan hash, así que no
    // hay conflicto de caché entre builds.
    outDir: '../backend/public',
    emptyOutDir: false,
  },
})
