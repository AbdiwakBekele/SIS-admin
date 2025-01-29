import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import viteCompression from "vite-plugin-compression"
import { resolve } from "path"

export default defineConfig(() => {
    console.log("\n =========== BUILDING SITE =========== ")

    return {
        plugins: [
            laravel({
                buildDirectory: "site/build",
                input: ["resources/js/site.js", "resources/sass/site.scss"],
                refresh: true,
            }),
            viteCompression({
                filter: /\.(js|css|scss)$/i,
            }),
        ],
        css: {
            postcss: "./vite/site/",
            preprocessorOptions: {
                scss: {
                    api: "modern-compiler",
                },
            },
        },
        resolve: {
            alias: {
                "@": resolve(__dirname, "./resources"),
                "@sass": resolve(__dirname, "./resources/sass"),
                "@plugins": resolve(__dirname, "./resources/plugins"),
                "@js": resolve(__dirname, "./resources/js"),
                "@vendor": resolve(__dirname, "./vendor"),
            },
        },
    }
})
