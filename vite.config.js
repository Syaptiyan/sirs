import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    outDir: 'public/build',
    rollupOptions: {
      input: {
        app: 'resources/js/app.js',
        css: 'resources/css/app.css',
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: '[ext]/[name].[ext]',
      },
    },
    manifest: true,
  },
  server: {
    host: 'localhost',
    port: 5173,
    proxy: {
      '/': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
    },
  },
})
