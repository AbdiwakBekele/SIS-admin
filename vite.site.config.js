import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
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
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
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
                vue: "vue/dist/vue.esm-bundler.js",
                "@": resolve(__dirname, "./resources"),
                "@sass": resolve(__dirname, "./resources/sass"),
                "@var": resolve(__dirname, "./resources/var"),
                "@plugins": resolve(__dirname, "./resources/plugins"),
                "@js": resolve(__dirname, "./resources/js"),
                "@repositories": resolve(
                    __dirname,
                    "./resources/js/repositories"
                ),
                "@core": resolve(__dirname, "./resources/js/core"),
                "@routes": resolve(__dirname, "./resources/js/routes"),
                "@stores": resolve(__dirname, "./resources/js/stores"),
                "@services": resolve(__dirname, "./resources/js/services"),
                "@views": resolve(__dirname, "./resources/js/views"),
                "@vendor": resolve(__dirname, "./vendor"),
            },
        },
    }
})
