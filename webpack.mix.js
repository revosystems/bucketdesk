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
        'resources/js/jquery.tagsinput.min.js',  //http://xoxco.com/projects/code/tagsinput/
    ], 'public/js/app.js')
    .less('resources/less/app.less', '../resources/css/style.css')
    .styles([
        'resources/css/jquery.tagsinput.min.css',
        'resources/css/style.css'
    ],'public/css/app.css');
