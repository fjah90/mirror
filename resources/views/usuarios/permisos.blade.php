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
        <h1 style="font-weight: bolder;">Roles</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                  <h3 class="panel-title text-right" style="height:30px;">
                    <span class="pull-left p-10">Lista de Roles</span>
              
                    
         
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla" class="table table-bordred" \>
                      <thead>
                        <tr style="background-color:#12160F">
                          <th class="color_text">#</th>
                          <th class="color_text">Nombre</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($roles as $rol)
                        <tr>
                          <td>{{$rol->id}}</td>
                          <td>{{$rol->name}}</td>
                          <td class="text-right">
                            <a class="btn btn-xs btn-success" title="Editar Permisos"
                              href="{{route('permisos.edit', ['rol'=>$rol->id])}}">
                              <i class="fas fa-pencil-alt"></i>
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
