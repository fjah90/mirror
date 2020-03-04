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

    .font-bold {
      font-weight: bold;
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

    ul {
      margin: 0px;
      padding-left: 20px;
    }
  </style>
  <style>
    /* custom */
    .table-cotizacion>tbody {
      border-bottom: 0.5px solid #000;
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
      border: 1px solid #000;
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
        <p class="font-bold">Purchase</p>
        <p>Order: {{$orden->numero}}</p>
        <p>Date: {{$orden->fechaPDF}}</p>
      </div>
      <div class="col-lg-7">
        <p class="text-uppercase text-right">Intercorp Contract Resources, s.a. de c.v.</p>
        <p class=" text-right font-small">Av. Juan Salvador Agraz 50, Oficina 702, Santa Fe Cuajimalpa.</p>
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
                TO:
              </th>
              <th class="text-center" style="width:50%; padding:3px 0;">
                INVOICE TO / CONSIGNED TO:
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="bordered">
                <p class="text-uppercase">{{$orden->proveedor->empresa}}</p>
                <p class="text-uppercase">
                  {{$orden->proveedor->calle}}
                  {{$orden->proveedor->numero}}
                </p>
                <p class="text-uppercase">
                  {{$orden->proveedor->colonia}}
                  {{$orden->proveedor->estado}}
                  {{$orden->proveedor->cp}}
                </p>
                <p class="text-uppercase">T. {{$orden->contacto->telefono}}</p>
                <p class="text-uppercase">ATN: {{$orden->contacto->nombre}}</p>
                <p>
                  email:
                  <a href="mailto:{{$orden->contacto->email}}">
                    {{$orden->contacto->email}}
                  </a>
                </p>
              </td>
              <td class="bordered">
                <p>INTERCORP CONTRACT RESOURCES, S.A. DE C.V.</p>
                <p>Av. Juan Salvador Agraz 50, Oficina 702, Santa Fe Cuajimalpa</p>
                <p>Ciudad de Mexico, 05348 Mexico</p>
                <p>T. +52 (55) 5557-5214</p>
                <p style="width:49%; display:inline-block;">ATTN: ABRAHAM SHVEID</p>
                <p style="width:49%; display:inline-block;"><a href="mailto:abraham@intercorp.mx">abraham@intercorp.mx</a></p>
                <p>RFC: ICR090925MV2</p>
              </td>
            </tr>
            <tr>
              <td class="bordered" style="padding:0;">
                <table style="margin:0; width:100%;">
                  <tr>
                    <td style="vertical-align:top; width:15%;">PROJECT:</td>
                    <td class="text-uppercase">
                      <strong>
                        {{$orden->numero_proyecto}}
                        {{$orden->proyecto_nombre}}
                      </strong>
                    </td>
                  </tr>
                  <tr>
                    <td>DELIVERY:</td>
                    <td class="text-uppercase">{{$orden->proyecto->cotizacion->entrega}}</td>
                  </tr>
                  <tr>
                    <td>D. POINT:</td>
                    <td class="text-uppercase">
                      {{$orden->proyecto->cliente->ciudad}}
                      {{$orden->proyecto->cliente->estado}}
                    </td>
                  </tr>
                  <tr>
                    <td>FREIGHT:</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>PRICES:</td>
                    <td class="text-uppercase">{{$orden->moneda}}</td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top;">TERMS:</td>
                    <td class="text-uppercase">CREDIT FOR {{$orden->proveedor->dias_credito}} DAYS</td>
                  </tr>
                </table>
              </td>
              <td class="bordered">
                <p class="text-center font-small "><strong>DELIVER TO:</strong></p>
                <p class="text-uppercase">{{$orden->proyecto->cotizacion->lugar}}</p>
                @if($orden->aduana)
                <p class="text-uppercase">By: {{$orden->aduana->compañia}}</p>
                <p class="text-uppercase">{{$orden->aduana->contacto}}</p>
                <p class="text-uppercase">{{$orden->aduana->telefono}}</p>
                <p class="text-uppercase">{{$orden->aduana->email}}</p>
                <p class="text-uppercase">{{$orden->aduana->direccion}}</p>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <table class="table table-cotizacion" style="margin:-5px 0px 0px;">
          <thead style="background-color:#000; color:#fff;">
            <tr>
              <th class="text-center" style="width:10%; padding:3px 0 1px;">QUANTITY</th>
              <th class="text-center" style="width:74%; padding:3px 0 1px;">DESCRIPTION</th>
              <th class="text-center" style="width:13%; padding:3px 0 1px;">UNIT PRICE</th>
              <th class="text-center" style="width:13%; padding:3px 0 1px;">AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orden->entradas as $entrada)
            <tr>
              @if($entrada->cantidad_convertida)
              <td class="text-center">@format_number($entrada->cantidad_convertida) <br /> {{$entrada->conversion_ingles}}</td>
              @else
              <td class="text-center">@format_number($entrada->cantidad) <br /> {{$entrada->medida_ingles}}</td>
              @endif
              <td>
                <table style="width:100%; margin:0;">
                  <tr>
                    <td style="vertical-align: top;">
                      @foreach($entrada->descripciones as $descripcion)
                      @if(($descripcion->valor||$descripcion->valor_ingles) && $entrada->producto->descripciones[$loop->index]->descripcionNombre->aparece_orden_compra )
                      <p>
                        <span>@text_capitalize($descripcion->name): </span>
                        @if($descripcion->valor_ingles)
                        <span class="text-uppercase">{{$descripcion->valor_ingles}}</span>
                        @elseif($descripcion->valor)
                        <span class="text-uppercase">{{$descripcion->valor}}</span>
                        @endif
                      </p>
                      @endif
                      @endforeach
                      @if($entrada->conversion)
                      <p>
                        @format_number($entrada->cantidad) {{$entrada->medida_ingles}} =
                        @format_number($entrada->cantidad_convertida) {{$entrada->conversion_ingles}}
                      </p>
                      @endif
                    </td>
                    <td style="width:100px;">
                      @if ($entrada->producto->foto)
                      <img src="{{$entrada->producto->foto}}" alt="foto" style="width:100px; height:100px;" />
                      @endif
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
                @if($orden->entradas->count()==1)
                <div style="height: 200px; background-color:white;"></div>
                @elseif($orden->entradas->count()==2)
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
      <div class="col-lg-12" style="height:70px;">
      </div>
    </div> --}}

    {{-- <div class="row footer" style="page-break-inside: avoid;"> --}}
    <div class="row" style="page-break-inside: avoid;">
      <div class="col-lg-12 bordered" style="padding: 0px; margin-left:15px;">
        <table class="" style="margin-bottom:0; width:100%;">
          <tr>
            <td class="text-right" style="width:90%;"><strong>MERCHANDISE AMOUNT:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($orden->subtotal)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>FREIGHT:</strong></td>
            <td class="text-right" style="width:10%;">@format_money(0)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>OTHER:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($orden->iva)</td>
          </tr>
          <tr>
            <td class="text-right" style="width:90%;"><strong>INVOICE TOTAL:</strong></td>
            <td class="text-right" style="width:10%;">@format_money($orden->total)</td>
          </tr>
        </table>
      </div>
      <div class="clearfix">
      </div>
      <div class="col-lg-12 bordered" style="padding: 0px; margin-left:15px;">
        <table style="margin: 0px; width:100%;">
          <tr class="font-small">
            <td style="width:70%; text-transform: none;">
              <p><strong>Remarks:</strong></p>
              <ul>
                <li>PLEASE SEND WITH THE ORDER THE INVOICE AND PACKING LIST.</li>
                <li>ORDER MUST SHIP COMPLETE; NO PARTIAL SHIPMENTS WILL BE ACCEPTED.</li>
                <li>PLEASE CONFIRM THE RECEPTION OF THIS ORDER AND ESTIMATED SHIPPING DATE.</li>
              </ul>
            </td>
            <td class="text-center" style="width:30%; text-transform: none;">
              <img style="margin-top:1em; width:200px; height:auto;" src="{{$orden->firmaAbraham}}" alt=" " />
              <hr style="border:0.5px solid #000; width:70%; margin-top:-5px; margin-bottom:0px;" />
              <img style="width:150px; height:auto;" src="{{public_path().'/images/logo.jpg'}}" alt="INTERCORP" />
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div><!-- Container -->
</body>

</html>
