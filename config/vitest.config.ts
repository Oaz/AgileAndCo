import { defineConfig } from 'vitest/config'

export default defineConfig({
    test: {
        include: ['frontend/**/*.{test,spec}.ts'],
        reporters: ['default'],
        coverage: {
            provider: 'v8',
            reporter: ['text', 'html', 'lcov'],
            include: [
                'frontend/**/*.{js,ts,jsx,tsx,svelte}'
            ],
            exclude: [
                'node_modules/**',
                'dist/**',
                '**/*.d.ts',
                '**/*.test.{js,ts}',
                '**/*.spec.{js,ts}'
            ]
        }
    }
})