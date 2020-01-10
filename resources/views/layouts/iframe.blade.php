<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>
    @section('title')
      Intercorp
    @show
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}"/>
  <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/favicon.ico') }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
  <!-- global css -->
  <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ mix('css/app.css')}}" rel="stylesheet" type="text/css">
  <!-- end of global css -->
  @yield('header_styles')
</head>

<body>
<!-- header logo: style can be found in header-->

    @yield('content')
  

<!-- ./wrapper -->
<!-- global js -->
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/accounting@0.4.1/accounting.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.2/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
$(function() {
  $('#logout-anchor').on('click', function(){
    $('#logout-form').submit();
  });
});
var translationsES = {
  countrySelectorLabel: 'Codigo de Pais',
  countrySelectorError: '',
  phoneNumberLabel: '',
  example: 'Ejemplo:'
};
var localeES = {
  uiv: {
    datePicker: {
      clear: 'Limpiar',
      today: 'Hoy',
      month: 'Mes',
      month1: 'Enero',
      month2: 'Febrero',
      month3: 'Marzo',
      month4: 'Abril',
      month5: 'Mayo',
      month6: 'Junio',
      month7: 'Julio',
      month8: 'Agosto',
      month9: 'Septiembre',
      month10: 'Octubre',
      month11: 'Noviembre',
      month12: 'Diciembre',
      year: 'Año',
      week1: 'Lun',
      week2: 'Mar',
      week3: 'Mie',
      week4: 'Jue',
      week5: 'Vie',
      week6: 'Sab',
      week7: 'Dom'
    },
    timePicker: {
      am: 'AM',
      pm: 'PM'
    },
    modal: {
      cancel: 'Cancelar',
      ok: 'OK'
    }
  }
}
var colorPallet = [
  //America’s Top 50 Crayola's Crayon Colors from
  //http://www.sensationalcolor.com/color-resources/favorite-crayon-colors-11997
  //https://www.crayola.com/explore-colors/
  '#0066ff', //Blue 1903
  '#02a4d3', //Cerulean 1990
  '#652dc1', //Purple Heart 1997
  '#003366', //Midnight Blue 1958
  '#458b74', //Aquamarine 195
  '#00cc99', //Caribbean Green 1998
  '#c3cde6', //Periwinkle 1949
  '#1560bd', //Denim 1993
  '#da3287', //Cerise 1993
  '#67c8ff', //battery charge blue 1972
  '#93ccea', //Cornflower 1949
  '#ed0a3f', //Red 1903
  '#0095b6', //Blue Green 1934
  '#ff00cc', //Hot Magenta 1972
  '#6456b7', //Blue Violet 1934
  '#009dc4', //Pacific Blue 1993
  '#d6aedd', //Purple Mountain Majesty 1993
  '#00755e', //Tropical Rain Forest 1993
  '#c62d42', //Brick Red 1949
  '#4f69c6', //Indigo 1999
  '#01a368', //Green 1903
  '#c9a0dc', //Wisteria 1993
  '#5fa777', //Forest Green 1957
  '#6b3fa0', //Royal Purple 1990
  '#c154c1', //Fuchsia 1990
  '#7ba05b', //Asparagus 1993
  '#ff00cc', //Purple Pizzazz 1991
  '#803790', //Vivid Violet 1998
  '#76d7ea', //Sky Blue 1957
  '#843179', //Plum 1957
  '#ccff00', //Electric Lime 1991
  '#0066cc', //Navy Blue 1957
  '#00cccc', //Robin Egg Blue 1993
  '#000000', //Black 1903
  '#008080', //Teal Blue 1990
  '#fd0e35', //Scarlet 1998
  '#ffb7d5', //Cotton Candy 1998
  '#f653a6', //Magenta 1903
  '#9999cc', //Blue Bell 1998
  '#fe6f5e', //Bittersweet 1949
  '#ffa6c9', //Carnation Pink 1949
  '#e30b5c', //Razzmatazz 1993
  '#c9c0bb', //Silver 1949
  '#e97451', //Burnt Sienna 1903
  '#29ab87', //Jungle Green 1990
  '#ff9966', //Atomic Tangerine 1972
  '#9a68af', //ultra violet 1997
  '#01796f', //Pine Green 1949
  '#66ff66', //Screamin Green 1972
  '#ffff66', //Laser Lemon 1972
];
</script>
<!-- end of page level js -->
<!-- page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
