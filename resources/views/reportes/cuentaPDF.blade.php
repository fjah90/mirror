<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <style>
    html {
      font-family: sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    body {
      margin: 25px;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 10px;
      line-height: 1.1;
      color: #333;
      background-color: #fff;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
      font-family: inherit;
      font-weight: 500;
      color: inherit;
    }

    h1,
    .h1,
    h2,
    .h2,
    h3,
    .h3 {
      margin-top: 20px;
      margin-bottom: 10px;
    }

    h1,
    .h1 {
      font-size: 36px;
    }

    h2,
    .h2 {
      font-size: 30px;
    }

    h3,
    .h3 {
      font-size: 24px;
    }

    h4,
    .h4 {
      font-size: 18px;
    }

    h5,
    .h5 {
      font-size: 14px;
    }

    h6,
    .h6 {
      font-size: 12px;
    }

    p {
      margin: 0;
    }

    caption {
      padding-top: 8px;
      padding-bottom: 8px;
      color: #777;
      text-align: left;
    }

    table {
      border-spacing: 0;
      border-collapse: collapse;
      background-color: transparent;
      margin: 5px 0 10px;
    }

    th {
      text-align: left;
    }

    table col[class*="col-"] {
      position: static;
      display: table-column;
      float: none;
    }

    table td[class*="col-"],
    table th[class*="col-"] {
      position: static;
      display: table-cell;
      float: none;
    }

    .table {
      width: 100%;
      max-width: 100%;
      margin-bottom: 10px;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
      padding: 2px;
      vertical-align: top;
      border-top: 1px solid #000;
    }

    .table>thead>tr>th {
      vertical-align: bottom;
      border-bottom: 2px solid #000;
    }

    .table>caption+thead>tr:first-child>th,
    .table>colgroup+thead>tr:first-child>th,
    .table>thead:first-child>tr:first-child>th,
    .table>caption+thead>tr:first-child>td,
    .table>colgroup+thead>tr:first-child>td,
    .table>thead:first-child>tr:first-child>td {
      border-top: 0;
    }

    .table-condensed th,
    .table-condensed td {
      padding: 5px;
    }

    .table-bordered {
      border: 1px solid #000;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 2px;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }

    .table tr.success td,
    .table tr.success th,
    .table td.success,
    .table th.success {
      background-color: #dff0d8;
    }

    .table tr.info td,
    .table tr.info th,
    .table td.info,
    .table th.info {
      background-color: #d9edf7;
    }

    .table tr.warning td,
    .table tr.warning th,
    .table td.warning,
    .table th.warning {
      background-color: #fcf8e3;
    }

    .table tr.danger td,
    .table tr.danger th,
    .table td.danger,
    .table th.danger {
      background-color: #f2dede;
    }
  </style>
  <title>Cotizacion</title>
  <style>
    /* Rows and columns */
    .row {
      width: 744px;
    }

    .landscape .row {
      width: 1070px;
    }

    .row::after {
      display: block;
      content: "";
      clear: both;
    }

    .col-lg-1,
    .col-lg-2,
    .col-lg-3,
    .col-lg-4,
    .col-lg-5,
    .col-lg-6,
    .col-lg-7,
    .col-lg-8,
    .col-lg-9,
    .col-lg-10,
    .col-lg-11,
    .col-lg-12 {
      position: relative;
      min-height: 1px;
      padding-left: 15px;
      padding-right: 15px;
      float: left;
    }

    /* Todas las columnas tienen -30px por el margin de row */
    .col-lg-1 {
      width: 31.9752px;
    }

    .landscape .col-lg-1 {
      width: 69.131px;
    }

    .col-lg-2 {
      width: 93.9504px;
    }

    .landscape .col-lg-2 {
      width: 148.262px;
    }

    .col-lg-3 {
      width: 156px;
    }

    .landscape .col-lg-3 {
      width: 237.5px;
    }

    .col-lg-4 {
      width: 217.9752px;
    }

    .landscape .col-lg-4 {
      width: 326.631px;
    }

    .col-lg-5 {
      width: 279.9504px;
    }

    .landscape .col-lg-5 {
      width: 415.762px;
    }

    .col-lg-6 {
      width: 342px;
    }

    .landscape .col-lg-6 {
      width: 505px;
    }

    .col-lg-7 {
      width: 403.9752px;
    }

    .landscape .col-lg-7 {
      width: 594.131px;
    }

    .col-lg-8 {
      width: 465.9504px;
    }

    .landscape .col-lg-8 {
      width: 683.262px;
    }

    .col-lg-9 {
      width: 528px;
    }

    .landscape .col-lg-9 {
      width: 772.5px;
    }

    .col-lg-10 {
      width: 589.9752px;
    }

    .landscape .col-lg-10 {
      width: 861.631px;
    }

    .col-lg-11 {
      width: 651.9504px;
    }

    .landscape .col-lg-11 {
      width: 950.762px;
    }

    .col-lg-12 {
      width: 714px;
    }

    .landscape .col-lg-12 {
      width: 1040px;
    }
  </style>
  <style>
    /* textos */
    .text-uppercase {
      text-transform: uppercase;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .text-danger {
      color: #FF7A7A;
    }

    /* fuentes */
    .font-small {
      font-size: 9px;
    }

    .font-big {
      font-size: 12px;
    }
  </style>
  <style>
    @page {
      margin: 0;
    }

    .border {
      border: 1px solid #000;
    }

    .page-title {
      border-bottom: 1px solid #ccc;
    }

    .general-info {
      background: #E6E6E6;
    }

    .pull-right {
      float: right;
    }

    .pull-left {
      float: left;
    }

    .bordered {
      border: 0.5px solid #000;
    }

    .clearfix:after {
      content: "";
      display: table;
      clear: both;
    }

    .hidden {
      visibility: hidden;
    }

    .page-break {
      page-break-after: always;
    }

    .container {
      margin: 0px;
      padding: 0px;
      width: 100%;
      max-height: 100%;
    }

    .header,
    .footer {
      width: 100%;
      position: absolute;
    }

    .header {
      top: 0px;
    }

    .footer {
      bottom: 0px;
    }

    .margBott10 {
      margin-bottom: 10px;
    }

    .margTop10 {
      margin-top: 10px;
    }

    ul {
      margin: 0px;
      padding-left: 20px;
    }
  </style>
  <style>
    /* custom */
    .table-cotizacion {
      margin: -5px 0px 0px;
    }

    .table-cotizacion>thead {
      background-color: #000;
      color: #fff;
    }

    .table-cotizacion>tbody {
      border-bottom: 0.5px solid #000;
      /* border: none; */
    }

    .table-cotizacion>tbody tr:last-child {
      margin-bottom: 5px !important;
      border-top: 5px solid blue;
      /* border: none; */
    }

    .table-cotizacion>tbody>tr>th,
    .table-cotizacion>tbody>tr>td,
    .table-cotizacion>tfoot>tr>th,
    .table-cotizacion>tfoot>tr>td {
      border: 0;
    }

    .table-cotizacion>tbody>tr>th,
    .table-cotizacion>tbody>tr>td {
      border-left: 0.5px solid #000;
    }

    .table-cotizacion>tbody>tr>th:last-child,
    .table-cotizacion>tbody>tr>td:last-child {
      border-right: 0.5px solid #000;
    }

    .table-cotizacion>tfoot {
      border: 0.5px solid #000;
    }

    .cuadro_magico {
      border-left: 0.5px solid #000;
      border-right: 0.5px solid #000;
      border-bottom: 0.5px solid #000;
      width: 100%;
      height: 400px;
      float: left;
    }
  </style>
</head>

<body>
  <div class="container">

    <div class="row">
      <div class="col-lg-12 text-center">
        <img style="width:200px; height:auto;" src="{{public_path().'/images/logo.jpg'}}" alt="INTERCORP" />
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-1"></div>
      <div class="col-lg-10">
        <hr style="border: 2.5px solid #000;">
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-12">
        <h4>REPORTE CUENTA CLIENTE</h4>
      </div>
    </div>

    <!-- Espacio para que el footer no se sobreponga a la tabla -->
     <div class="row">
      <div class="col-lg-12">
        @foreach($datos as $key => $proyecto)
          <table class="table table-cotizacion">
          <thead>
            <tr>
              <th class="text-center" style=" padding:3px 0 1px;">Fecha</th>
              <th class="text-center" style=" padding:3px 0 1px;">Numero de Cotizacion</th>
              <th class="text-center" style=" padding:3px 0 1px;">Fecha de Aprobacion</th>
              <th class="text-center" style=" padding:3px 0 1px;">Moneda</th>
              <th class="text-center" style=" padding:3px 0 1px;">Monto</th>
              <th class="text-center" style=" padding:3px 0 1px;">Facturado</th>
              <th class="text-center" style=" padding:3px 0 1px;">Por Facturar</th>
              <th class="text-center" style=" padding:3px 0 1px;">Pagado</th>
              <th class="text-center" style=" padding:3px 0 1px;">Pendiente</th>        
            </tr>
          </thead>
          <tbody>
            @foreach($proyecto as $cuenta)
            <tr>
              <td class="text-center">{{$cuenta->cotizacionFecha}}</td>
              <td class="text-center">{{$cuenta->cotizacion_id}}</td>
              <td class="text-center">{{$cuenta->aprobadoEn }}</td>
              <td class="text-center">{{$cuenta->moneda}}</td>
              <td class="text-center">{{$cuenta->total}}</td>
              <td class="text-center">{{$cuenta->facturado}}</td>
              <td class="text-center">{{$cuenta->total - $cuenta->facturado}}</td>
              <td class="text-center">{{$cuenta->pagado}}</td>
              <td class="text-center">{{$cuenta->pendiente}}</td>
            </tr>
            @endforeach
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td>MXN</td>
              <td>Total : {{$totales[$key][0]}}</td>
              <td>Total Facturado :{{$totales[$key][1]}}</td>
              <td>Total Por Facturar :{{$totales[$key][4]}}</td>
              <td>Total Pagado :{{$totales[$key][2]}}</td>
              <td>Total Pendiente :{{$totales[$key][3]}}</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td>Dolares</td>
              <td>Total : {{$totales[$key][5]}}</td>
              <td>Total Facturado : {{$totales[$key][6]}}</td>
              <td>Total Por Facturar :{{$totales[$key][9]}}</td>
              <td>Total Pagado : {{$totales[$key][7]}}</td>
              <td>Total Pendiente :{{$totales[$key][8]}}</td>
            </tr>
          </tbody>
        </table>
        @endforeach
      </div>
    </div> 

    {{-- <div class="row footer" style="page-break-inside: avoid;"> --}}
    <div class="row" style="page-break-inside: avoid;">
      <div class="bordered" style="margin:5px 15px; 0">
        <table class="" style="margin-bottom:0; width:100%;">
        </table>
      </div>
    </div>

  </div><!-- Container -->

  <script type="text/php">
      if ( isset($pdf) ) {
          $font = $fontMetrics->getFont('helvetica');
          $pdf->page_text(540, 786, "{PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));
      }
  </script>
  
</body>

</html>
