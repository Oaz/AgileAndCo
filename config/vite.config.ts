import { defineConfig } from 'vite';
import svgr from 'vite-plugin-svgr';
import image from '@rollup/plugin-image';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import commonjs from '@rollup/plugin-commonjs';
import path from 'path';

const isStandalone = process.env.BUILD_TARGET !== 'bga';

const root = isStandalone
    ? path.resolve(__dirname, '../frontend/explorations')
    : path.resolve(__dirname, '../frontend/lib');

const build = isStandalone ? {
    outDir: path.resolve(__dirname, '../local/standalone'),
    minify: false,
    lib: {
        entry: path.resolve(__dirname, '../frontend/explorations/main.ts'),
        fileName: (format:any) => 'demo_agileandco.js',
        formats: ['amd'],
    },
    rollupOptions: {
        format: 'amd',
        treeshake: false,
        plugins: [
        ],
    }
} : {
    outDir: path.resolve(__dirname, '../remote'),
    minify: true,
    lib: {
        entry: path.resolve(__dirname, '../frontend/lib/integration.js'),
        fileName: (format:any) => 'modules/frontend.js',
        formats: ['amd'],
    },
    rollupOptions: {
        format: 'amd',
        treeshake: false,
        plugins: [
        ],
    }
};

export default defineConfig(

    {
        base:'./',
        plugins: [
            svelte(),
            commonjs(),
            svgr(),
            image(),
        ],
        root: root,
        build: build,
    });
