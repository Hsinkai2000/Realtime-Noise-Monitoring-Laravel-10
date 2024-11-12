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
mix.sass("resources/scss/login.scss", "public/css")
    .sass("resources/scss/home.scss", "public/css")
    .sass("resources/scss/base.scss", "public/css")
    .sass("resources/scss/project-admin.scss", "public/css")
    .sass("resources/scss/project.scss", "public/css")
    .sass("resources/scss/measurement_point.scss", "public/css")
    .sass("resources/scss/pdf.scss", "public/css")
    .sass("resources/scss/noise_meters.scss", "public/css")
    .sass("resources/scss/concentrator.scss", "public/css");

mix.postCss("resources/css/app.css", "public/css", [
    //
]);
