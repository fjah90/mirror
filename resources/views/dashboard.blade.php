@extends('layouts/default')

{{-- Page title --}}
@section('title')
Dashboard | @parent
@stop

@section('header_styles')
<style>
  .card-header {
    border: none;
  }

  .card-title {
    font-size: 16px;
    color: #777;
  }

  /*mail tiles sales, visits etc*/
  .card-box {
    padding: 21px 16px 30px;
    border: 1px solid rgba(54, 64, 74, 0.12);
    -webkit-border-radius: 3px;
    border-radius: 3px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin-bottom: 25px;
    background-color: #ffffff;
    color: #777;
  }

  .widget-bg-color-icon .bg-icon {
    height: 80px;
    width: 80px;
    text-align: center;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    -moz-border-radius: 50%;
    background-clip: padding-box;
  }

  .widget-bg-color-icon .bg-icon i {
    font-size: 34px;
    line-height: 65px;
  }

  .widget-bg-color-icon h3 {
    margin-top: 9px;
  }

  .widget-bg-color-icon p {
    margin: 0;
  }

  /*sales chart*/
  #sales_chart {
    height: 300px;
    width: 100%;
  }

  .morris-hover.morris-default-style .morris-hover-point {
    color: #555 !important;
  }

  /*timeline widget*/
  .widget-timeline {
    color: #555;
  }

  .widget-timeline ul {
    padding-left: 0;
  }

  .widget-timeline ul li {
    padding: 18px 3px;
  }

  .widget-timeline img {
    width: 39px;
    height: 39px;
    border-radius: 50%;
  }

  .widget-timeline .timeline {
    padding-left: 51px;
  }

  /*table*/
  .product-details .panel-body {
    padding-bottom: 0;
  }

  #product-details tbody>tr>td {
    padding: 13px;
  }

  #product-details tbody>tr>td:last-child {
    width: 160px;
  }

  #product_status canvas {
    margin-bottom: -2px;
  }

  .update_btn {
    margin-top: -8px;
  }

  /************* lobibox css ***************/
  .lobibox-notify-wrapper.right {
    z-index: 9999999;
  }

  .lobibox-notify {
    box-shadow: -8px 6px 20px 2px #aaa;
    width: 460px !important;
    height: 115px;
    border-radius: 10px;
  }

  .lobibox-notify-msg {
    font-size: 14px;
    margin-left: 30px;
  }

  .lobibox-notify .lobibox-notify-title {
    font-size: 18px;
    margin-left: 30px;
    font-family: 'Montserrat Alternates', sans-serif;
  }

  .lobibox-notify .lobibox-notify-body {
    margin: 20px 20px 10px 125px;
  }

  .lobibox-notify-msg {
    margin-top: 8px;
    font-family: 'Montserrat Alternates', sans-serif;
  }

  .lobibox-notify-body {
    border-left: 2px solid #fff;
  }

  .lobibox-notify-icon img {
    margin-left: 15px;
  }

  .lobibox-notify.lobibox-notify-info {
    background: linear-gradient(to bottom right, #12b1ec, #1FC0C0);
    border-color: transparent;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Dashboard</h1>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fas fa-user-check text-warning"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>3752</b></h3>
          <p>Clientes Registrados</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fab fa-opencart text-success"></i>
        </div>
        <div class="text-right">
          <h3><b id="widget_count3">3251</b></h3>
          <p>Proyectos en proceso</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="far fa-thumbs-up text-danger"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>1532</b></h3>
          <p>Proyectos Aprobados</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fa fa-hand-pointer-o text-info"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>1252</b></h3>
          <p>Ordenes en Proceso</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading">
          <h3 class="panel-title">Próximas Actividades</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table table-striped text-center" id="product-details">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center"><strong>Id</strong></th>
                      <th class="text-center"><strong>Product Name</strong></th>
                      <th class="text-center"><strong>Price</strong></th>
                      <th class="text-center"><strong>Sales</strong></th>
                      <th class="text-center"><strong>Ratings</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>7897898</td>
                      <td>Becky Barnes</td>
                      <td>$340</td>
                      <td>3,080</td>
                      <td class="text-warning">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star-half-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>7897898</td>
                      <td>Jayden Hunter</td>
                      <td>$340</td>
                      <td>3,080</td>
                      <td class="text-warning">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                      </td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>7897898</td>
                      <td>Wallace boyd</td>
                      <td>$340</td>
                      <td>3,080</td>
                      <td class="text-warning">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star-half-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                      </td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>7897898</td>
                      <td>Randy Spencer</td>
                      <td>$340</td>
                      <td>3,080</td>
                      <td class="text-warning">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star-half-o" aria-hidden="true"></i>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12 p-0">
              <span id="product_status"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@stop
