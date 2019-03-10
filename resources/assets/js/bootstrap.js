window.$ = window.jQuery = require('jquery');
window.swal = require('sweetalert2');
require('bootstrap-sass');
require('./metisMenu');
require('./leftmenu');
window.JSZip = require( 'jszip' );
var pdfMake = require('pdfmake/build/pdfmake.js');
var pdfFonts = require('pdfmake/build/vfs_fonts.js');
pdfMake.vfs = pdfFonts.pdfMake.vfs;
require( 'datatables.net-dt' );
require( 'datatables.net-buttons' );
require( 'datatables.net-buttons/js/buttons.flash.js' );
require( 'datatables.net-buttons/js/buttons.html5.js' );
require( 'datatables.net-buttons/js/buttons.print.js' );
require( 'datatables.net-responsive-dt' );
window.objectToFormData = require('object-to-formdata');
require( 'tinymce' );
require( 'tinymce/themes/modern/theme' );
require('tinymce/plugins/lists');

import axios from 'axios';
import Vue from 'vue';
import * as uiv from 'uiv';
import Editor from '@tinymce/tinymce-vue';

window.Vue = Vue;
window.axios = axios;
window.axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest'
};
// window.axios.defaults.headers.common = {
//     'X-CSRF-TOKEN': window.Laravel.csrfToken,
//     'X-Requested-With': 'XMLHttpRequest'
// };

Vue.use(uiv);
Vue.component('tinymce-editor', Editor);
Vue.component('select2multags', require('./components/select2multags.vue'));

$.extend($.fn.dataTable.defaults, {
  responsive: true,
  lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
  language: {
    "decimal": "",
    "emptyTable": "Sin datos...",
    "info": "Mostrando _START_ hasta _END_ de _TOTAL_ resultados",
    "infoEmpty": "Mostrando 0 hasta 0 de 0 resultados",
    "infoFiltered": "(filtered from _MAX_ total entries)",
    "infoPostFix": "",
    "thousands": ",",
    "lengthMenu": "Mostrar _MENU_ resultados",
    "loadingRecords": "Cargando...",
    "processing": "Un momento...",
    "search": "Buscar:",
    "zeroRecords": "No se encontro resultados",
    "paginate": {
      "first": "Primero",
      "last": "Ultimo",
      "next": "Siguiente",
      "previous": "Anterior"
    },
    "aria": {
      "sortAscending": ": activate to sort column ascending",
      "sortDescending": ": activate to sort column descending"
    }
  }
});
$.fn.dataTable.Responsive.breakpoints = [
    { name: 'desktop', width: Infinity },
    { name: 'tablet',  width: 1024 },
    { name: 'fablet',  width: 768 },
    { name: 'phone',   width: 480 }
];
