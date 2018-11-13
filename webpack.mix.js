const mix = require('laravel-mix');

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

mix.babel([
    'vendor/badchoice/thrust/src/resources/js/thrust.min.js',
    ],
    'public/js/app.js')
   .less('resources/less/app.less', 'public/css');
