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

mix.js('resources/assets/js/app.js', 'public/js')
   .extract([
     'jquery',
     'bootstrap-sass',
     'sweetalert2',
     'jszip',
     'pdfmake/build/pdfmake.js',
     'pdfmake/build/vfs_fonts.js',
     'datatables.net-dt',
     'datatables.net-buttons',
     'datatables.net-buttons/js/buttons.flash.js',
     'datatables.net-buttons/js/buttons.html5.js',
     'datatables.net-buttons/js/buttons.print.js',
     'datatables.net-responsive-dt',
     'datatables.net-rowreorder-dt',
     'uiv',
     'bootstrap-fileinput',
     'bootstrap-fileinput/themes/fa/theme.js',
     'bootstrap-fileinput/js/locales/es.js',
     'object-to-formdata',
     'tinymce',
     '@tinymce/tinymce-vue',
     'select2'
   ])
   .autoload({
     jquery: ['$', 'window.jQuery', 'jQuery', 'jquery'],
    });
mix.sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/vendor.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
