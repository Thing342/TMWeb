let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('resources/assets/ts/app.ts', 'public/dist/js')
    .ts('resources/assets/ts/route.ts', 'public/dist/js')
    .ts('resources/assets/ts/mapview.ts', 'public/dist/js')
    .sass('resources/assets/sass/app.sass', 'public/dist/css')
    .version()
    .sourceMaps();