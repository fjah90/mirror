<!DOCTYPE html>
<html>
<head>
  <title>Intercorp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ URL::asset('assets/img/logos/icono.png') }}" sizes="32x32">
	<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('assets/img/logos/icono.png') }}">
  <!-- global css -->
  <link href="{{asset('assets/vendors/iCheck/css/all.css')}}" rel="stylesheet">
  <!-- end of global css -->
  <!-- page level css -->
  <link href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet"/>
  <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
  <link href="{{asset('assets/css/pages/login_register.css')}}" rel="stylesheet">
  <!-- end of page level css-->
</head>

<body id="sign-in">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 signin-form">
            <div class="panel-header">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center">
                            <img src="{{asset('assets/img/logos/logo_trans.png')}}" alt="Logo">
                        </h2>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form id="authentication" class="sign_validator" method="POST" action="{{ route('login') }}">
                      {{ csrf_field() }}
                        <div class="col-md-12">
                            @if ($errors->has('email'))
                                <span class="help-block text-center" style="color: #FF7A7A;">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <div class="form-group">
                                <label for="email">Usuario</label>
                                <input type="text" class="form-control form-control-lg" id="email"
                                  name="email" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            @if ($errors->has('password'))
                                <span class="help-block text-center" style="color: #FF7A7A;">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                 name="password" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" value="Acceder" class="btn btn-primary btn-block"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr class="separator">
                        </div>
                        <div class="col-md-12 text-center">
                            <span>&#169; 2018 789.mx</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- begining of page level js -->
<script src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/pages/login_register.js')}}"></script>
<!-- end of page level js -->
</body>

</html>
