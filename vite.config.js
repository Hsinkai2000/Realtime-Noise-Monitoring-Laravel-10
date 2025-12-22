import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0",
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/scss/login.scss",
                "resources/scss/home.scss",
                "resources/scss/base.scss",
                "resources/scss/project-admin.scss",
                "resources/js/project-admin.js",
                "resources/scss/project.scss",
                "resources/js/project.js",
                "resources/scss/measurement_point.scss",
                "resources/js/measurement_point.js",
                "resources/scss/pdf.scss",
                "resources/js/pdf.js",
                "resources/scss/noise_meters.scss",
                "resources/js/noise_meters.js",
                "resources/scss/concentrator.scss",
                "resources/js/concentrator.js",
            ],
            refresh: true,
        }),
    ],
});
