@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cuenta por Pagar | @parent
@stop

@section('header_styles')
<!-- <style>
</style> -->
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Cuentas por Pagar</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Cuenta {{$cuenta->id}}: {{$cuenta->proyecto_nombre}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-10">
                <div class="form-group">
                  <label class="control-label">Proveedor</label>
                  <span class="form-control">{{$cuenta->proveedor_empresa}}</span>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Dias credito</label>
                  <span class="form-control">{{$cuenta->dias_credito}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Total {{$cuenta->moneda}}</label>
                  <span class="form-control">@format_money($cuenta->total)</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Facturado</label>
                  <span class="form-control">@format_money($cuenta->facturado)</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Pagado</label>
                  <span class="form-control">@format_money($cuenta->pagado)</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Pendiente</label>
                  <span class="form-control">@format_money($cuenta->pendiente)</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h4>Facturas en Cuenta</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Documento</th>
                        <th>Monto Total</th>
                        <th>Pagos</th>
                        <th>Monto Pendiente</th>
                        <th>Fecha Vencimiento</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($cuenta->facturas as $factura)
                      <tr>
                        <td>{{$factura->documento}}</td>
                        <td>@format_money($factura->monto)</td>
                        <td>
                          @foreach($factura->pagos as $index => $pago)
                            <span>{{$index+1}}.- @format_money($pago->monto)</span>
                            @if($pago->comprobante)
                            <a class="btn btn-xs btn-info" target="_blank" href="{{$pago->comprobante}}">
                              <i class="far fa-eye"></i>
                            </a>
                            @endif
                            <br />
                          @endforeach
                          <span>Total: @format_money($factura->pagado)</span><br />
                        </td>
                        <td>@format_money($factura->pendiente)</td>
                        <td>{{$factura->vencimiento_formated}}</td>
                        <td class="text-right">
                          <a class="btn btn-warning" title="PDF" href="{{$factura->pdf}}"
                            download="factura {{$factura->documento}}.pdf">
                            <i class="far fa-file-pdf"></i>
                          </a>
                          <a class="btn btn-default" title="XML" href="{{$factura->xml}}"
                            download="factura {{$factura->documento}}.xml">
                            <i class="far fa-file-excel"></i>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<!-- <script type="text/javascript">
</script> -->
@stop
