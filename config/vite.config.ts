import {defineConfig} from 'vite';
import svgr from 'vite-plugin-svgr';
import image from '@rollup/plugin-image';
import {svelte} from '@sveltejs/vite-plugin-svelte';
import commonjs from '@rollup/plugin-commonjs';
import path from 'path';

const root = (() => {
    switch (process.env.BUILD_TARGET) {
        case 'bga':
            return path.resolve(__dirname, '../frontend/lib');
        case 'mockups':
            return path.resolve(__dirname, '../frontend/explorations');
        case 'website':
            return path.resolve(__dirname, '../frontend/website');
        case 'banner':
            return path.resolve(__dirname, '../frontend/shared/banner');
        default:
            return undefined;
    }
})();

const build = (() => {
    switch (process.env.BUILD_TARGET) {
        case 'bga':
            return {
                outDir: path.resolve(__dirname, '../remote'),
                minify: true,
                lib: {
                    entry: path.resolve(__dirname, '../frontend/lib/integration.js'),
                    fileName: (format: any) => 'modules/frontend.js',
                    formats: ['amd'],
                },
                rollupOptions: {
                    format: 'amd',
                    treeshake: false,
                    plugins: [],
                }
            };
        case 'mockups':
            return {
                outDir: path.resolve(__dirname, '../local/standalone'),
                minify: false,
                lib: {
                    entry: path.resolve(__dirname, '../frontend/explorations/main.ts'),
                    fileName: (format: any) => 'demo_agileandco.js',
                    formats: ['amd'],
                },
                rollupOptions: {
                    format: 'amd',
                    treeshake: false,
                    plugins: [],
                }
            };
        case 'website':
            return {
                outDir: path.resolve(__dirname, '../local/website'),
                minify: false,
                lib: {
                    entry: path.resolve(__dirname, '../frontend/website/main.ts'),
                    fileName: (format: any) => 'agileandco.js',
                    formats: ['amd'],
                },
                rollupOptions: {
                    format: 'amd',
                    treeshake: false,
                    plugins: [],
                    input: {
                        main: path.resolve(__dirname, '../frontend/website/index.html'),
                        print: path.resolve(__dirname, '../frontend/website/print.html')
                    },
                    output: {
                        entryFileNames: '[name].js',
                        chunkFileNames: 'shared.[name].js',
                        assetFileNames: '[name].[ext]',
                    }

                }
            };
        default:
            return undefined;
    }
})();

export default defineConfig(
    {
        base: './',
        plugins: [
            svelte(),
            commonjs(),
            svgr(),
            image(),
        ],
        root: root,
        build: build,
    });
