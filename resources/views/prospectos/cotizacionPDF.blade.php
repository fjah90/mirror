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
      <div class="col-lg-5">
        <p>Cotizacion # {{$cotizacion->numero}}</p>
        <p>Fecha: {{$cotizacion->fechaPDF}}</p>
      </div>
      <div class="col-lg-7">
        <p class="text-uppercase text-right">Intercorp Contract Resources, s.a. de c.v.</p>
        <p class=" text-right font-small">Av. Juan Salvador Agraz 50, Oficina 702,  Lomas de Santa Fe.</p>
        <p class=" text-right font-small">Ciudad de Mexico, 05348 Mexico</p>
        <p class="text-right font-small">T. +52 (55) 5557-5214 intercorp.com.mx</p>
      </div>
    </div>

    <div class="row margTop10">
      <div class="col-lg-12">
        <table class="table" style="border-collapse: separate; border-spacing: 0 5px; margin:0px;">
          <thead style="background-color:#000; color:#fff;">
            <tr>
              <th class="text-center" style="width:50%; padding:3px 0;">
                Cliente:
              </th>
              <th class="text-center" style="width:50%; padding:3px 0;">
                Facturar a:
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="bordered">
                <p class="text-uppercase">{{$cotizacion->prospecto->cliente->nombre}}</p>
                <p class="text-uppercase">{{$cotizacion->prospecto->cliente->direccion}}</p>
                <p class="text-uppercase">ATN: {{$cotizacion->contacto->nombre}}</p>
                <p class="text-uppercase">T. {{$cotizacion->contacto->telefono}}</p>
                <p>
                  Email:
                  <a href="mailto:{{$cotizacion->contacto->email}}">
                    {{$cotizacion->contacto->email}}
                  </a>
                </p>
              <td class="bordered text-uppercase">
                @if($cotizacion->facturar)
                <p class="text-uppercase">RFC: {{$cotizacion->rfc}}</p>
                <p class="text-uppercase">Razon social: {{$cotizacion->razon_social}}</p>
                <p class="text-uppercase">
                  {{$cotizacion->calle}} {{$cotizacion->nexterior}}
                  @if($cotizacion->ninterior) Int. {{$cotizacion->ninterior}} @endif
                </p>
                <p class="text-uppercase">{{$cotizacion->colonia}} {{$cotizacion->cp}}</p>
                <p class="text-uppercase">{{$cotizacion->ciudad}} {{$cotizacion->estado}}</p>
                @endif
              </td>
            </tr>
            <tr>
              <td class="bordered" style="padding:0;">
                <table style="margin:0; width:100%;">
                  <tr>
                    <td style="vertical-align:top">Proyecto:</td>
                    <td class="text-uppercase">
                      <strong>{{$cotizacion->prospecto->nombre}}</strong>
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top">Entrega:</td>
                    <td class="text-uppercase">{{$cotizacion->entrega}}</td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top">Fletes:</td>
                    <td class="text-uppercase">{{$cotizacion->fletes}}</td>
                  </tr>
                  <tr>
                    <td>Precios:</td>
                    <td class="text-uppercase">{{$cotizacion->moneda}}</td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top;">Condiciones:</td>
                    <td class="text-uppercase">{{$cotizacion->condiciones->nombre}}</td>
                  </tr>
                </table>
              </td>
              <td class="bordered">
                <p class="text-center font-small "><strong>Enviar a:</strong></p>
                <p class="text-uppercase">{{$cotizacion->contacto_nombre}} {{$cotizacion->contacto_telefono}}</p>
                <p>Email: <a href="mailto:{{$cotizacion->contacto_email}}">{{$cotizacion->contacto_email}}</a>
                <p class="text-uppercase">
                  {{$cotizacion->dircalle}} {{$cotizacion->dirnexterior}}
                  @if($cotizacion->dirninterior) Int. {{$cotizacion->dirninterior}} @endif
                </p>
                <p class="text-uppercase">{{$cotizacion->dircolonia}} {{$cotizacion->dircp}}</p>
                <p class="text-uppercase">{{$cotizacion->dirciudad}} {{$cotizacion->direstado}}</p>
              </td>
            </tr>
            @if($cotizacion->notas)
            <tr>
              <td colspan="2" class="bordered">
                <p class="text-danger"><strong>Notas</strong></p>
                <p class="text-uppercase">{{$cotizacion->notas}}</p>
              </td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        {{-- <div class="cuadro_magico"></div> --}}
        <table class="table table-cotizacion">
          <thead>
            <tr>
              <th class="text-center" style="width:10%; padding:3px 0 1px;">Cantidad</th>
              <th class="text-center" style="width:74%; padding:3px 0 1px;">Descripciones</th>
              <th class="text-center" style="width:13%; padding:3px 0 1px;">Precio Unitario</th>
              <th class="text-center" style="width:13%; padding:3px 0 1px;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cotizacion->entradas as $entrada)
            <tr>
              <td class="text-center">@format_number($entrada->cantidad) <br /> {{$entrada->medida}}</td>
              <td>
                <table style="width:100%; margin:0;">
                  <tr>
                    <td style="vertical-align: top;">
                      @foreach($entrada->descripciones as $descripcion)
                      @if($descripcion->valor)
                      <p>
                        <span>@text_capitalize($descripcion->{$nombre}): </span>
                        <span class="text-uppercase">{{$descripcion->valor}}</span>
                      </p>
                      @endif
                      @endforeach
                      @if($entrada->observaciones && $entrada->observaciones!='<ul></ul>')
                      <p>
                        <span>@if($nombre=='nombre') Observaciones: @else Remarks: @endif</span>
                        {!! $entrada->observaciones !!}
                      </p>
                      @endif
                    </td>
                    <td style="width:100px;">
                      @foreach($entrada->fotos as $foto)
                      <img src="{{$foto}}" alt="foto" style="width:100px; height:100px;" />
                      <br />
                      @endforeach
                    </td>
                  </tr>
                </table>
              </td>
              <td class="text-right">@format_money($entrada->precio)</td>
              <td class="text-right">@format_money($entrada->importe)</td>
            </tr>
            @endforeach
            <tr>
              <td></td>
              <td>
                @if($cotizacion->entradas->count()==1)
                <div style="height: 200px; background-color:white;"></div>
                @elseif($cotizacion->entradas->count()==2)
                <div style="height: 0px; background-color:white;"></div>
                @endif
              </td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Espacio para que el footer no se sobreponga a la tabla -->
    {{-- <div class="row">
      <div class="col-lg-12" style="height:180px;">
      </div>
    </div> --}}

    {{-- <div class="row footer" style="page-break-inside: avoid;"> --}}
    <div class="row" style="page-break-inside: avoid;">
      <div class="bordered" style="margin:5px 15px; 0">
        <table class="" style="margin-bottom:0; width:100%;">
          <tr>
            <td class="text-right" style="width:90%;"><strong>Subtotal:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->subtotal)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>IVA 16%:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->iva)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>Total {{$cotizacion->moneda}}:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($cotizacion->total)</td>
          </tr>
        </table>
      </div>

      <div class="bordered" style="margin:5px 15px 0;">
        <table style="margin: 0px; width:100%;">
          <tr class="font-small">
            <td style="width:70%; text-transform: none; vertical-align: top;">
              <p class="margTop10" style="margin-left:10px;">
                @if($cotizacion->observaciones)
                <strong>@if($nombre=='nombre') Observaciones: @else Remarks: @endif</strong>
                @endif
              </p>
              {!! $cotizacion->observaciones !!}
            </td>
            <td class="text-center" style="width:30%; text-transform: none;">
              @if($cotizacion->user->id==2)
              <!-- "Abraham Shveid" -->
              <img style="margin-top:10px; width:170px; height:auto;" src="{{$cotizacion->user->firma}}" alt=" " />
              <hr style="border:0.5px solid #000; width:70%; margin-top:-5px; margin-bottom:0px;" />
              @elseif($cotizacion->user->id==5)
              <!-- "Elena Salido" -->
              <img style="margin-top:10px; width:170px; height:auto;" src="{{$cotizacion->user->firma}}" alt=" " />
              <hr style="border:0.5px solid #000; width:70%; margin-top:0px; margin-bottom:0px;" />
              @else
              <img style="margin-top:10px; width:170px; height:auto;" src="{{$cotizacion->user->firma}}" alt=" " />
              <hr style="border:0.5px solid #000; width:70%; margin-top:-15px; margin-bottom:0px;" />
              @endif
              <p style="">{{$cotizacion->user->name}}</p>
              <p style="">Intercorp Contract Resources</p>
              <hr style="border:0.5px solid #000; width:70%; margin-top:60px; margin-bottom:0px;" />
              <p style="margin: 5px 0 10px;">Aprobacíon Del Cliente</p>
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div><!-- Container -->
</body>

</html>
