import { chromium, type Browser, type Page } from '@playwright/test';
import { createServer, type ViteDevServer } from 'vite';
import path from "path";
import {svelte} from '@sveltejs/vite-plugin-svelte';

async function takeScreenshot(browser: Browser, language:string, path:string): Promise<void> {
    const page: Page = await browser.newPage();
    await page.goto(`http://localhost:3000?lang=${language}`);
    await page.waitForSelector('.banner');
    const banner = await page.$('.banner');
    if (!banner) {
        throw new Error('Banner element not found');
    }
    await banner.screenshot({
        path: path,
        quality: 80
    });
    console.log(`Banner image ${path} generated successfully!`);
}

async function generateBanners(): Promise<void> {
    let server: ViteDevServer | null = null;
    let browser: Browser | null = null;

    try {
        const rootPath = path.join(process.cwd(), '/frontend/shared/banner');
        console.log('Server root path:', rootPath);

        server = await createServer({
            root: rootPath,
            server: {
                port: 3000
            },
            plugins: [
                svelte(),
            ],
        });

        await server.listen();
        browser = await chromium.launch();
        for (const lang of ['en', 'fr']) {
            await takeScreenshot(browser, lang, path.join(rootPath, `banner_${lang}.jpg`));
        }

    } catch (error) {
        console.error('Error generating banner:', error);
        process.exit(1);
    } finally {
        await browser?.close();
        await server?.close();
    }
}

generateBanners();