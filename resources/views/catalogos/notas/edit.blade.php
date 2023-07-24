@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Nota | @parent
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
                    <div class="card-header">Editar nota</div>

                    <div class="card-body">
                        <form action="{{ route('notas.update', $nota->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $nota->titulo) }}" required>
                                @error('titulo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contenido">Contenido</label>
                                <textarea name="contenido" id="contenido" class="form-control @error('contenido') is-invalid @enderror" rows="5" required>{{ old('contenido', $nota->contenido) }}</textarea>
                                @error('contenido')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection