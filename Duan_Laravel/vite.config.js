import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: { https: false},
  plugins: [
        laravel([
            'resources/css/app.css', 
            'resources/js/app.js',
        ]),
    ],
});


