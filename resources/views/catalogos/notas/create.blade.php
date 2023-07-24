@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Nota | @parent
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
                    <div class="card-header">Crear nota</div>

                    <div class="card-body">
                        <form action="{{ route('notas.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" name="titulo" id="titulo"
                                    class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') }}"
                                    required>
                                @error('titulo')
                                    <span class="invalid-feedback">{{ $titulo }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contenido">Contenido</label>
                                <textarea name="contenido" id="contenido" class="form-control @error('contenido') is-invalid @enderror" rows="5"
                                    required>{{ old('contenido') }}</textarea>
                                @error('contenido')
                                    <span class="invalid-feedback">{{ $contenido }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Crear</button>
                            <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
