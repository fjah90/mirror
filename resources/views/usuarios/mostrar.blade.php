@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Permisos | @parent
@stop

@section('header_styles')
<style>
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;">Permisos de {{$permiso->name}}</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
        <form action="/permisos/{{$rol->id}}/actualizar" method="post" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                  <h3 class="panel-title text-right" style="height:30px;">
                    <span class="pull-left p-10">Lista de Permisos  </span>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla" class="table table-bordred" \>
                      <thead>
                        <tr style="background-color:#12160F">
                          <th class="color_text">#</th>
                          <th class="color_text">Nombre</th>
                          <th class="color_text">Activo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($permisos as $permiso)
                        <tr>
                          <td>{{$permiso->id}}</td>
                          <td>{{$permiso->name}}</td>
                          <td><input type="checkbox" name="permisos_ids[]" value="{{$permiso->id}}" {{ $permisosrol->contains($permiso->id) ? 'checked' : '' }}></input></td>  
                        </tr>
                        @endforeach
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <button type="submit" class="btn btn-dark" style="background-color: rgb(18, 22, 15); color: rgb(182, 137, 17);">Guardar</button>
            </div>
          </form>
        </div>
    </section>
    <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

<!-- <script>
const app = new Vue({
    el: '#content',
    data: {},
    mounted(){
      this.dataTable = $("#tabla").DataTable({
        dom: 'lfrtip',
      });
    },
    methods: {
      algo(){console.log('hola');}
    }
});
</script> -->
@stop
