import { defineConfig } from 'vite';
import svgr from 'vite-plugin-svgr';
import image from '@rollup/plugin-image';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import commonjs from '@rollup/plugin-commonjs';
import path from 'path';

export default defineConfig(({ command }) => {
    const isBuildMockups = process.env.BUILD_TARGET !== 'bga';

    return {
        base:'./',
        plugins: [
            svelte(),
            commonjs(),
            svgr(),
            image(),
        ],
        root: isBuildMockups ? path.resolve(__dirname, '../frontend/explorations') : path.resolve(__dirname, '../frontend/lib'),
        build: {
            outDir: path.resolve(__dirname, isBuildMockups ? '../local/mockups' : '../remote'),
            minify: false,
            lib: {
                entry: path.resolve(__dirname, '../frontend/lib/integration.js'),
                fileName: (format) => `modules/frontend.js`,
                formats: ['amd'],
            },
            rollupOptions: {
                input: isBuildMockups
                    ? path.resolve(__dirname, '../frontend/explorations/main.ts')
                    : path.resolve(__dirname, '../frontend/lib/integration.js'),
                output: {
                    format: 'amd',
                    entryFileNames: (chunkInfo) => {
                        return isBuildMockups ? 'mockups.js' : 'modules/frontend.js';
                    },
                    assetFileNames: (assetInfo) => {
                        if (assetInfo.name.endsWith('.svg')) {
                            return 'img/[name].svg';
                        }
                        return '[name][extname]';
                    },
                },
                treeshake: false,
                plugins: [
                ],
            },
        },
    };
});
