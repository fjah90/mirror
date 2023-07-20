@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Directo| @parent
@stop

@section('header_styles')
    <style>
        table td:first-child span.fa-grip-vertical:hover {
            cursor: move;
        }
    .color_text{
    color:#B3B3B3;
     }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;">Cotizar Directo Proyecto </h1>
    </section>
    <!-- Main content -->

    <!-- /.content -->

@stop