import { defineConfig } from 'vite'
import svgr from 'vite-plugin-svgr';
import image from '@rollup/plugin-image';
import { svelte } from '@sveltejs/vite-plugin-svelte'
import commonjs from '@rollup/plugin-commonjs';
import path from 'path';

export default defineConfig({
    plugins: [
        svelte(),
        commonjs(),
        svgr(),
        image(),
    ],
    root: path.resolve(__dirname, '../frontend/explorations'),
    build: {
        outDir: path.resolve(__dirname, '../remote'),
        minify: false,
        // sourcemap: true,
        rollupOptions: {
            input: path.resolve(__dirname, '../frontend/lib/integration.js'),
            output: {
                format: 'amd',
                entryFileNames: `modules/frontend.js`,
                assetFileNames: `agileandco.css`,
            },
            treeshake: false,
        },
    },
})
