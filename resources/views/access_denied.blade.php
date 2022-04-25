<!DOCTYPE html>
<html>

<head>
    <title>Acceso Denegado | Reune Direccion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/logos/icono.png')}}"/>
    <!-- global level css -->
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
    <!-- end of global css-->
    <!-- page level styles-->
    <link href="{{asset('assets/css/pages/error_pages.css')}}" rel="stylesheet">
    <!-- end of page level styles-->
    <style>

    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <div class="error_content">
            <div class="text-center">
              <h2>
                <img src="{{asset('images/logo.jpg')}}" alt="Logo">
              </h2>
            </div>
            <div class="text-center">
              <div>
                <div class="error">
                  <span class="folded-corner"></span>
                  <p class="type"><i class="fas fa-ban" style="font-size:150px;"></i></p>
                  <p class="type-text" style="margin-top:0;">Acceso Denegado</p>
                  <p class="message">
                    Sistema bloqueado por falta de pago, por favor realice su pago correspondiente y notifiquelo al administrador.
                    <!--
                    No tiene permisos para acceder a esta area.
                    
                     Regresar al
                    <a href="/dashboard">Dashboard</a>.
                  -->
                  <p>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
