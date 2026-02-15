import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    timeout: 120_000,
    expect: { timeout: 30_000 },
    use: {
        baseURL: 'http://localhost:8787',
    },
    webServer: {
        command: 'npx serve docs -l 8787 --no-clipboard',
        port: 8787,
        reuseExistingServer: true,
        timeout: 10_000,
    },
    reporter: 'list',
});
