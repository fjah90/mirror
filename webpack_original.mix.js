const {mix} = require('laravel-mix');

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

var vendors = 'resources/assets/vendors/';
var resourcesAssets = 'resources/assets/';
var srcCss = resourcesAssets + 'css/';
var srcJs = resourcesAssets + 'js/';

//destination path configuration
var dest = 'public/assets/';
var destFonts = dest + 'fonts/';
var destCss = dest + 'css/';
var destJs = dest + 'js/';
var destVendors = dest + 'vendors/';


/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
    'jquery': vendors + 'jquery/dist/',
    'jqueryui': vendors + 'jquery-ui/',
    'bootstrap': vendors + 'bootstrap/dist/',
    'animate': vendors + 'animate.css/',
    'fontawesome': vendors + 'font-awesome/',
    'metisMenu': vendors + 'metisMenu/dist/',
    'buttons': vendors + 'Buttons/',
    'bootstrapValidator': vendors + 'bootstrapvalidator/dist/',
    'wenk': vendors + 'wenk/dist/',
    'icheck': vendors + 'iCheck/',
    "bootstapfile": vendors + 'bootstrap-filestyle/',
    'select2': vendors + 'select2/dist/',
    'morrisjs': vendors + 'morris.js/',
    'raphael': vendors + 'raphael/',
    'daterangepicker': vendors + 'bootstrap-daterangepicker/',
    'moment': vendors + 'moment/',
    'clockpicker': vendors + 'clockpicker/dist/',
    'airdatepicker': vendors + 'air-datepicker/dist/',
    'switch': vendors + 'bootstrap-switch/dist/',
    'colorpicker': vendors + 'mjolnic-bootstrap-colorpicker/dist/',
    'notific': vendors + 'notific8/dist/',
    'sweetalert2': vendors + 'sweetalert2/dist/',
    'flotchart': vendors + 'flotchart/',
    'flotspline': vendors + 'flot-spline/js/',
    'flottooltip': vendors + 'flot.tooltip/js/',
    'knob': vendors + 'jquery-knob/js/',
    'fullcalendar': vendors + 'fullcalendar/dist/',
    'chartjs': vendors + 'Chart.js/dist/',
    'chartist': vendors + 'chartist/dist/',
    'datatables': vendors + 'datatables/media/',
    'datatables_buttons': vendors + 'datatables.net-buttons/',
    'datatables_buttons_bs': vendors + 'datatables.net-buttons-bs/',
    'jqueryrepeater': vendors + 'jquery.repeater/',
    'markjs': vendors + 'mark.js/dist/',
    'datatablesmarkjs': vendors + 'datatables.mark.js/dist/',
    'bootstraptable': vendors + 'bootstrap-table/dist/',
    "tableExportjqueryplugin": vendors + "tableExport.jquery.plugin/",
    'twtrBootstrapWizard': vendors + 'twitter-bootstrap-wizard/',
    'jasnyBootstrap': vendors + 'jasny-bootstrap/dist/',
    'datetimepicker': vendors + 'eonasdan-bootstrap-datetimepicker/build/',
    'select2BootstrapTheme': vendors + 'select2-bootstrap-theme/dist/',
    'gmaps': vendors + 'gmaps/',
    'jvectormap': vendors + 'bower-jvectormap/',
    'datatablesresponsive': vendors + 'datatables-responsive/'
};


//delete all files first

// Copy fonts straight to public
mix.copy(paths.bootstrap + 'fonts', destFonts);
mix.copy(paths.bootstrap + 'fonts/glyphicons-halflings-regular.ttf', destFonts + 'bootstrap');
mix.copy(paths.bootstrap + 'fonts/glyphicons-halflings-regular.woff', destFonts + 'bootstrap');
mix.copy(paths.bootstrap + 'fonts/glyphicons-halflings-regular.woff2', destFonts + 'bootstrap');
mix.copy(paths.fontawesome + 'fonts', destFonts);

//COPY CSS,JS TO PUBLIC
mix.copy(srcCss, destCss,false);
mix.copy(srcJs, destJs,false);

//  jQuery
mix.copy(paths.jquery + 'jquery.min.js', destJs);

//  jQuery UI
mix.copy(paths.jqueryui + 'jquery-ui.min.js', destVendors + 'jquery-UI');

//  Bootstrap
mix.copy(paths.bootstrap + 'css/bootstrap.min.css', destCss);
mix.copy(paths.bootstrap + 'js/bootstrap.min.js', destJs);

// animate
mix.copy(paths.animate + 'animate.min.css', destVendors + 'animate');

//font-awesome
mix.copy(paths.fontawesome + 'css/font-awesome.min.css', destCss);

// metis menu
mix.copy(srcJs + 'metisMenu.js', destJs);

// Buttons
mix.copy(paths.buttons + 'css/buttons.css', destVendors + 'Buttons/css');
mix.copy(paths.buttons + 'js/buttons.js', destVendors + 'Buttons/js');

// bootstrapvalidator
mix.copy(paths.bootstrapValidator + 'css/bootstrapValidator.min.css', destVendors + 'bootstrapvalidator/css');
mix.copy(paths.bootstrapValidator + 'js/bootstrapValidator.min.js', destVendors + 'bootstrapvalidator/js');

//wenk
mix.copy(paths.wenk + 'wenk.min.css', destVendors + 'wenk');

//icheck
mix.copy(paths.icheck + 'icheck.js', destVendors + 'iCheck/js');
mix.copy(paths.icheck + 'skins/', destVendors + 'iCheck/css', false);

//bootstrap file style
mix.copy(paths.bootstapfile + 'src/bootstrap-filestyle.min.js', destVendors + 'bootstapfile/js');

//select2
mix.copy(paths.select2 + 'css/select2.min.css', destVendors + 'select2/css');
mix.copy(paths.select2 + 'js/select2.js', destVendors + 'select2/js');
mix.copy(paths.select2BootstrapTheme + 'select2-bootstrap.css', destVendors + 'select2/css');

//morris.js
mix.copy(paths.morrisjs + 'morris.css', destVendors + 'morrisjs/css');
mix.copy(paths.morrisjs + 'morris.min.js', destVendors + 'morrisjs/js');

//raphael morris dep
mix.copy(paths.raphael + 'raphael.min.js', destVendors + 'raphael');

// moment
mix.copy(paths.moment + 'min/moment.min.js', destVendors + 'moment/js');

// daterange picker
mix.copy(paths.daterangepicker + 'daterangepicker.js', destVendors + 'daterangepicker/js');
mix.copy(paths.daterangepicker + 'daterangepicker.css', destVendors + 'daterangepicker/css');

//clockpicker
mix.copy(paths.clockpicker + 'bootstrap-clockpicker.min.css', destVendors + 'clockpicker/css');
mix.copy(paths.clockpicker + 'bootstrap-clockpicker.min.js', destVendors + 'clockpicker/js');

// air datepicker
mix.copy(paths.airdatepicker + 'css/datepicker.min.css', destVendors + 'airdatepicker/css');
mix.copy(paths.airdatepicker + 'js/datepicker.min.js', destVendors + 'airdatepicker/js');
mix.copy(paths.airdatepicker + 'js/i18n/datepicker.en.js', destVendors + 'airdatepicker/js');

// bootstrap switch
mix.copy(paths.switch + 'css/bootstrap3/bootstrap-switch.css', destVendors + 'bootstrap-switch/css');
mix.copy(paths.switch + 'js/bootstrap-switch.js', destVendors + 'bootstrap-switch/js');

// bootstrap color picker
mix.copy(paths.colorpicker + 'css/bootstrap-colorpicker.min.css', destVendors + 'colorpicker/css');
mix.copy(paths.colorpicker + 'js/bootstrap-colorpicker.min.js', destVendors + 'colorpicker/js');
mix.copy(paths.colorpicker + 'img/bootstrap-colorpicker', destVendors + 'colorpicker/img/bootstrap-colorpicker');

// notifications
mix.copy(paths.notific + 'jquery.notific8.min.css', destVendors + 'notific/css');
mix.copy(paths.notific + 'jquery.notific8.min.js', destVendors + 'notific/js');

// Sweet Alert
mix.copy(paths.sweetalert2 + 'sweetalert2.min.css', destVendors + 'sweetalert2/css');
mix.copy(paths.sweetalert2 + 'sweetalert2.min.js', destVendors + 'sweetalert2/js');

// flot charts
mix.copy(paths.flotchart + 'jquery.flot.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.stack.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.crosshair.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.time.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.selection.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.symbol.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.resize.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.categories.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.pie.js', destVendors + 'flotchart/js');
mix.copy(paths.flottooltip + 'jquery.flot.tooltip.js', destVendors + 'flot.tooltip/js');
mix.copy(paths.flotspline + 'jquery.flot.spline.min.js', destVendors + 'flotspline/js');

// knob
mix.copy(paths.knob + 'jquery.knob.js', destVendors + 'jquery-knob/js');

//fullcalendar
mix.copy(paths.fullcalendar + 'fullcalendar.min.css', destVendors + 'fullcalendar/css');
mix.copy(paths.fullcalendar + 'fullcalendar.min.js', destVendors + 'fullcalendar/js');

// Chart.js
mix.copy(paths.chartjs + 'Chart.js', destVendors + 'chartjs/js');

//chartist
mix.copy(paths.chartist + 'chartist.min.css', destVendors + 'chartist/css');
mix.copy(paths.chartist + 'chartist.min.js', destVendors + 'chartist/js');


//datatables
mix.copy(paths.datatables + 'css/dataTables.bootstrap.min.css', destVendors + 'datatables/css');
mix.copy(paths.datatables_buttons_bs + 'css/buttons.bootstrap.min.css', destVendors + 'datatables/css');
mix.copy(paths.datatables + 'images', destVendors + 'datatables/images');
mix.copy(paths.datatables + 'js/jquery.dataTables.min.js', destVendors + 'datatables/js');
mix.copy(paths.datatables + 'js/dataTables.bootstrap.min.js', destVendors + 'datatables/js');
mix.copy(paths.datatables_buttons + 'js/dataTables.buttons.min.js', destVendors + 'datatables/js');
mix.copy(paths.datatables_buttons + 'js/buttons.print.min.js', destVendors + 'datatables/js');
mix.copy(paths.datatables_buttons + 'js/buttons.html5.min.js', destVendors + 'datatables/js');
mix.copy(paths.datatables_buttons_bs + 'js/buttons.bootstrap.min.js', destVendors + 'datatables/js');

// datatables responsive
mix.copy(paths.datatablesresponsive + 'css/responsive.dataTables.scss', destVendors + 'datatables/css');
mix.copy(paths.datatablesresponsive + 'js/dataTables.responsive.js', destVendors + 'datatables/js');

// jquery repeater
mix.copy(paths.jqueryrepeater + 'jquery.repeater.js', destVendors + 'jqueryrepeater/js');

//mark.js
mix.copy(paths.markjs + 'jquery.mark.js', destVendors + 'markjs/');

//datatables.mark.js
mix.copy(paths.datatablesmarkjs + 'datatables.mark.min.js', destVendors + 'datatablesmark.js/js');
mix.copy(paths.datatablesmarkjs + 'datatables.mark.min.css', destVendors + 'datatablesmark.js/css');

//bootstrap-table
mix.copy(paths.bootstraptable + 'bootstrap-table.min.css', destVendors + 'bootstrap-table/css');
mix.copy(paths.bootstraptable + 'bootstrap-table.min.js', destVendors + 'bootstrap-table/js');

//tableExport.jquery.plugin
mix.copy(paths.tableExportjqueryplugin + 'tableExport.min.js', destVendors + 'tableExportjqueryplugin/');

// gmaps
mix.copy(paths.gmaps + 'gmaps.min.js', destVendors + 'gmaps/js');

//  bower-jvectormap
mix.copy(paths.jvectormap + 'jquery-jvectormap-1.2.2.css', destVendors + 'bower-jvectormap/css');
mix.copy(paths.jvectormap + 'jquery-jvectormap-1.2.2.min.js', destVendors + 'bower-jvectormap/js');
mix.copy(paths.jvectormap + 'jquery-jvectormap-world-mill-en.js', destVendors + 'bower-jvectormap/js');


//form wizard page
mix.copy(paths.twtrBootstrapWizard + 'jquery.bootstrap.wizard.js', destVendors + 'bootstrapwizard/js');
mix.copy(paths.twtrBootstrapWizard + 'bootstrap/js/bootstrap.min.js', destVendors + 'bootstrapwizard/js');

//jasny-bootstrap
mix.copy(paths.jasnyBootstrap + 'css/jasny-bootstrap.css', destVendors + 'jasny-bootstrap/css');
mix.copy(paths.jasnyBootstrap + 'js/jasny-bootstrap.js', destVendors + 'jasny-bootstrap/js');

// bootstrap-datetimepicker
mix.copy(paths.datetimepicker + 'css/bootstrap-datetimepicker.min.css', destVendors + 'datetimepicker/css');
mix.copy(paths.datetimepicker + 'js/bootstrap-datetimepicker.min.js', destVendors + 'datetimepicker/js');

//sass compilation
mix.sass(resourcesAssets + 'sass/bootstrap/bootstrap.scss', destCss + 'bootstrap.css').options({
    processCssUrls: false
});
mix.sass(resourcesAssets + 'sass/buttons/buttons.scss', destCss + 'buttons_sass.css').options({
    processCssUrls: false
});
mix.sass(resourcesAssets + 'sass/custom.scss', destCss + 'custom.css').options({
    processCssUrls: false
});
mix.sass('public/assets/vendors/datatables/css/responsive.dataTables.scss', destCss + 'responsive.dataTables.css').options({
    processCssUrls: false
});


// all global css files into app.css
mix.combine(
    [
        'public/assets/css/bootstrap.css',
        'public/assets/css/font-awesome.min.css',
        'public/assets/css/metisMenu.css'
    ], destCss + 'app.css');


// all global js files into app.js
mix.combine(
    [
        'public/assets/js/jquery.min.js',
        'public/assets/js/bootstrap.min.js',
        'public/assets/js/metisMenu.js',
        'public/assets/js/leftmenu.js'
    ], destJs + 'app.js');


// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');
