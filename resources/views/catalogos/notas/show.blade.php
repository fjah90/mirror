@extends('layouts/default')

{{-- Page title --}}
@section('title') Ver Nota | @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $nota->titulo }}</div>

                    <div class="card-body">
                        <p>{{ $nota->contenido }}</p>
                        <p>Creada el {{ $nota->created_at }}</p>
                        <p>Actualizada el {{ $nota->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection