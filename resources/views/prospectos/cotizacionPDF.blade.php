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
      font-size: 12px;
      line-height: 1.1;
      color: #333;
      background-color: #fff;
    }
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
      font-family: inherit;
      font-weight: 500;
      color: inherit;
    }
    h1, .h1, h2, .h2, h3, .h3 {
      margin-top: 20px;
      margin-bottom: 10px;
    }
    h1, .h1 { font-size: 36px; }
    h2, .h2 { font-size: 30px; }
    h3, .h3 { font-size: 24px; }
    h4, .h4 { font-size: 18px; }
    h5, .h5 { font-size: 14px; }
    h6, .h6 { font-size: 12px; }
    p { margin: 0; }
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
      margin:5px 0 10px;
      text-transform: uppercase;
    }
    th { text-align: left; }
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
    .table > thead > tr > th, .table > tbody > tr > th,
    .table > tfoot > tr > th, .table > thead > tr > td,
    .table > tbody > tr > td, .table > tfoot > tr > td {
      padding: 2px;
      vertical-align: top;
      border-top: 1px solid #000;
    }
    .table > thead > tr > th {
      vertical-align: bottom;
      border-bottom: 2px solid #000;
    }
    .table > caption + thead > tr:first-child > th,
    .table > colgroup + thead > tr:first-child > th,
    .table > thead:first-child > tr:first-child > th,
    .table > caption + thead > tr:first-child > td,
    .table > colgroup + thead > tr:first-child > td,
    .table > thead:first-child > tr:first-child > td {
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
    .table-striped > tbody > tr:nth-of-type(odd) {
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
    .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6,
    .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {
    	position: relative;
    	min-height: 1px;
    	padding-left: 15px;
    	padding-right: 15px;
      float: left;
    }
    /* Todas las columnas tienen -30px por el margin de row */
    .col-lg-1{ width: 31.9752px; }
    .landscape .col-lg-1{ width: 69.131px; }
    .col-lg-2{ width: 93.9504px; }
    .landscape .col-lg-2{ width: 148.262px; }
    .col-lg-3{ width: 156px; }
    .landscape .col-lg-3{ width: 237.5px; }
    .col-lg-4{ width: 217.9752px; }
    .landscape .col-lg-4{ width: 326.631px; }
    .col-lg-5{ width: 279.9504px; }
    .landscape .col-lg-5{ width: 415.762px; }
    .col-lg-6{ width: 342px; }
    .landscape .col-lg-6{ width: 505px; }
    .col-lg-7{ width: 403.9752px; }
    .landscape .col-lg-7{ width: 594.131px; }
    .col-lg-8{ width: 465.9504px; }
    .landscape .col-lg-8{ width: 683.262px; }
    .col-lg-9{ width: 528px; }
    .landscape .col-lg-9{ width: 772.5px; }
    .col-lg-10{ width: 589.9752px; }
    .landscape .col-lg-10{ width: 861.631px; }
    .col-lg-11{ width: 651.9504px; }
    .landscape .col-lg-11{ width: 950.762px; }
    .col-lg-12{ width: 714px; }
    .landscape .col-lg-12{ width: 1040px; }
  </style>
  <style>
    /* textos */
    .text-uppercase {text-transform: uppercase;}
    .text-center {text-align: center;}
    .text-right {text-align: right;}
    .text-danger {color: #FF7A7A;}
    /* fuentes */
    .font-small{font-size: 11px;}
    .font-big{font-size: 14px;}
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
    .pull-right{
      float: right;
    }
    .pull-left{
      float: left;
    }
    .bordered{
      border: 1px solid #000;
    }
    .clearfix:after {
      content: "";
      display: table;
      clear: both;
    }
    .hidden{
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
    .footer .corte {
      border-top: 2px dashed #000;
      margin: -5px -50px 0;
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
    ul{
      margin: 0px;
      padding-left: 20px;
    }
  </style>
  <style>
    /* custom */
    .table-cotizacion > tbody > tr > th,
    .table-cotizacion > tbody > tr > td,
    .table-cotizacion > tfoot > tr > th,
    .table-cotizacion > tfoot > tr > td {
      border: 0;
    }
    .table-cotizacion > tbody > tr > th,
    .table-cotizacion > tbody > tr > td{
      border-left: 1px solid #000;
    }
    .table-cotizacion > tbody > tr > th:last-child,
    .table-cotizacion > tbody > tr > td:last-child{
      border-right: 1px solid #000;
    }
    .table-cotizacion > tfoot {border: 1px solid #000;}
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
        <hr style="border: 5px solid #000;">
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-6">
        <p>Cotizacion # {{$cotizacion->id}}</p>
        <p>Fecha: {{$cotizacion->fechaPDF}}</p>
      </div>
      <div class="col-lg-6">
        <p class="text-uppercase text-right">Intercorp Contract Resources, s.a. de c.v.</p>
        <p class="text-uppercase text-right">Moliere 61, Col. Polanco, Mexico, DF 11560, Mexico</p>
        <p class="text-right font-small">T. +52 (55) 5557-5214 intercorp.com.mx</p>
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-12">
        <table class="table" style="border-collapse: separate; border-spacing: 0 5px;">
          <thead style="background-color:#000; color:#fff;">
            <tr>
              <th class="text-center" style="width:50%; padding:3px 0;">
                CLIENTE:
              </th>
              <th class="text-center" style="width:50%; padding:3px 0;">
                FACTURAR A:
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="bordered">
                <p>{{$cotizacion->prospecto->cliente->nombre}}</p>
                <p>
                  {{$cotizacion->prospecto->cliente->calle}}
                  {{$cotizacion->prospecto->cliente->numero}}
                </p>
                <p>{{$cotizacion->prospecto->cliente->colonia}}</p>
                <p>T. {{$cotizacion->prospecto->cliente->telefono}}</p>
                <p>ATN: {{$cotizacion->prospecto->cliente->nombre}}</p>
                <p>
                  email:
                  <a href="mailto:{{$cotizacion->prospecto->cliente->email}}">
                    {{$cotizacion->prospecto->cliente->email}}
                  </a>
                </p>
              </td>
              <td class="bordered">{{$cotizacion->facturar}}</td>
            </tr>
            <tr>
              <td class="bordered" style="padding:0;">
                <table style="margin:0;">
                  <tr>
                    <td>PROYECTO:</td>
                    <td><strong>{{$cotizacion->prospecto->descripcion}}</strong></td>
                  </tr>
                  <tr>
                    <td>ENTREGA:</td>
                    <td>{{$cotizacion->entrega}}</td>
                  </tr>
                  <tr>
                    <td>FLETES:</td>
                    <td>INCLUIDOS A LA CD. DE MÉXICO</td>
                  </tr>
                  <tr>
                    <td>PRECIOS:</td>
                    <td>{{$cotizacion->moneda}}</td>
                  </tr>
                  <tr>
                    <td>CONDICIONES:</td>
                    <td>{{$cotizacion->condiciones->nombre}}</td>
                  </tr>
                </table>
              </td>
              <td class="bordered">
                <p class="text-center font-small "><strong>ENVIAR A:</strong></p>
                <p>{{$cotizacion->prospecto->cliente->nombre}}</p>
                <p>{{$cotizacion->lugar}}</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="bordered">
                <p class="text-danger"><strong>Notas</strong></p>
                <p>{{$cotizacion->notas}}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-12">
        <table class="table table-cotizacion" style="margin-bottom:0;">
          <thead style="background-color:#000; color:#fff;">
            <tr>
              <th class="text-center" style="width:10%; padding:3px 0 1px;">CANTIDAD</th>
              <th class="text-center" style="width:70%; padding:3px 0 1px;">DESCRIPCION</th>
              <th class="text-center" style="width:20%; padding:3px 0 1px;">PRECIO UNITARIO</th>
              <th class="text-center" style="width:10%; padding:3px 0 1px;">TOTAL</th>
            </tr>
          </thead>
          <tbody style="border-bottom: 1px solid #000;">
            @foreach($cotizacion->entradas as $entrada)
            <tr>
              <td class="text-center">@format_number($entrada->cantidad) {{$entrada->medida}}</td>
              <td>
                <table style="width:100%; margin:0;">
                  <tr>
                    <td>
                      <p>{{$entrada->producto->proveedor->empresa}}</p>
                      <p>{{ $entrada->producto->{$nombre} }}</p>
                      @foreach($entrada->descripciones as $descripcion)
                        <p>{{ $descripcion->{$nombre} }}: {{$descripcion->valor}}</p>
                      @endforeach
                    </td>
                    <td style="width:100px;">
                      @if($entrada->foto)
                      <img src="{{$entrada->foto}}" alt="foto" style="width:100px;" />
                      @endif
                    </td>
                  </tr>
                </table>
              </td>
              <td class="text-right">@format_money($entrada->precio)</td>
              <td class="text-right">@format_money($entrada->importe)</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Espacio para que el footer no se sobreponga a la tabla -->
    <div class="row">
      <div class="col-lg-12" style="height:70px;">
      </div>
    </div>

    <div class="row footer">
      <div class="col-lg-12 bordered" style="padding: 0px; margin-left:15px;">
        <table class="" style="margin-bottom:0; width:100%;">
          <tr>
            <td class="text-right" style="width:90%;"><strong>SUBTOTAL:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->subtotal)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>IVA 16%:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->iva)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>TOTAL {{$cotizacion->moneda}}:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->total)</td>
          </tr>
        </table>
      </div>
      <div class="clearfix">
      </div>
      <div class="col-lg-12 bordered" style="padding: 0px; margin-left:15px;">
        <table style="margin: 0px; width:100%;">
          <tr class="font-small">
            <td style="width:70%; text-transform: none;">
              <p><strong>OBSERVACIONES:</strong></p>
              {!! $cotizacion->observaciones !!}
            </td>
            <td class="text-center" style="width:30%; text-transform: none;">
              <p style="margin-top:1em;">{{$cotizacion->user->name}}</p>
              <p style="margin-bottom:1.5em;">Intercorp Contract Resources</p>
              <img style="width:200px; height:auto;" src="{{public_path().'/images/firma '}}{{$cotizacion->user->name}}.png" alt=" " />
              @if($cotizacion->user->name=="Abraham Shveid")
              <hr style="border:1px solid #000; width:70%; margin-top:-15px; margin-bottom:0px;" />
              <p style="font-size:10px; position:relative; top:-10px;">ATENCIÓN DEL CLIENTE</p>
              @elseif($cotizacion->user->name=="Elena Salido")
              <hr style="border:1px solid #000; width:70%; margin-top:-10px; margin-bottom:0px;" />
              <p style="font-size:10px; position:relative; top:-5px;">ATENCIÓN DEL CLIENTE</p>
              @else
              <hr style="border:1px solid #000; width:70%; margin-top:-25px; margin-bottom:0px;" />
              <p style="font-size:10px; position:relative; top:-20px;">ATENCIÓN DEL CLIENTE</p>
              @endif
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div><!-- Container -->
</body>
</html>
