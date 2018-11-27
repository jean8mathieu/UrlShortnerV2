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

mix.copy('resources/js/bootstrap.min.js', 'public/js')
    .copy('resources/js/jquery.min.js', 'public/js')
    .copy('resources/css/bootstrap.min.css', 'public/css')
    .js([
        'resources/js/shortner.js',
        'resources/js/authentication.js',
    ], 'public/js/custom.js')

    .js([
        'resources/js/admin.js'
    ], 'public/js/admin.js')
    .sass('resources/sass/app.scss', 'public/css')
    .version();