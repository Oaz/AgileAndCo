import {type Browser, chromium, type Page} from '@playwright/test';
import {createServer, type ViteDevServer} from 'vite';
import path from "path";
import {svelte} from '@sveltejs/vite-plugin-svelte';
import * as fs from "node:fs";

function isBannerMissing(filePath: string): boolean {
    try {
        return !fs.existsSync(filePath);
    } catch (error) {
        console.error(`Error checking file ${filePath}:`, error);
        return true;
    }
}

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

async function launchServer(rootPath: string) : Promise<ViteDevServer> {
    const server = await createServer({
        root: rootPath,
        server: {
            port: 3000
        },
        plugins: [
            svelte(),
        ],
    });

    await server.listen();
    return server;
}

function findMissingBanners(rootPath: string) {
    return ['en', 'fr']
        .map(lang => ({
            lang,
            path: path.join(rootPath, `output/banner_${lang}.jpg`)
        }))
        .filter(banner => isBannerMissing(banner.path));
}

async function generateBanners(): Promise<void> {
    let server: ViteDevServer | null = null;
    let browser: Browser | null = null;

    try {
        const rootPath = path.join(process.cwd(), '/frontend/shared/banner');
        const missingBanners = findMissingBanners(rootPath);
        if (missingBanners.length === 0) {
            console.log('All banners exist, skipping generation.');
            return;
        }

        console.log('Generating missing banners:', missingBanners.map(b => b.lang).join(', '));
        server = await launchServer(rootPath);
        browser = await chromium.launch();
        for (const banner of missingBanners) {
            await takeScreenshot(browser, banner.lang, banner.path);
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