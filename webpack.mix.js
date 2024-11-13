import mix from "laravel-mix";

mix.webpackConfig({
    resolve: {
        extensions: [".js", ".json", ".css"], // Ensure .js is included
    },
});

// JS Files
mix.js("resources/js/app.js", "public/js")
    .js("resources/js/project-admin.js", "public/js")
    .js("resources/js/project.js", "public/js")
    .js("resources/js/measurement_point.js", "public/js")
    .js("resources/js/pdf.js", "public/js")
    .js("resources/js/noise_meters.js", "public/js")
    .js("resources/js/concentrator.js", "public/js");

// SCSS Files
mix.sass("resources/scss/base.scss", "public/css");

mix.postCss("resources/css/app.css", "public/css", [
    //
]);
