<!DOCTYPE html>
<html>
<head>
  <title>ROBINSON</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ URL::asset('images/favicon.ico') }}" sizes="32x32">
	<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/favicon.ico') }}">
  <!-- global css -->
  <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
  <!-- end of global css -->
  <!-- page level css -->
  <link href="{{asset('assets/css/pages/login_register.css')}}" rel="stylesheet">
  <!-- end of page level css-->

  <style>
    html{
        background      : url("/images/unnamed2.png");
        background-size: cover;
        background-position: center -170px;
    }
    #sign-in, #sign-up {
        background: transparent;
    }
    .signup-form, .signin-form {
        background      : none;
        /* background      : url("/images/fondo.jpg"); */
        /* background-size: cover;  */
    }
    .signup-form:before, .signin-form:before {
        opacity       : 0;
        background    : #000;
    }
    .signup-form label, .signin-form label {
        font-size: 16px;
        color: #FFF;
    }
    .form-control {
        color: #000;
        background-color: #FFF;
        border: none;
        border-radius: 10px;
    }
    input[type="submit"] {
        background: #686767;
        color: #FFF;
        border: none;
        font-size: 16px;
        padding: 6px 60px;
        letter-spacing: 1px;
        border-radius: 10px;
    }
  </style>
</head>

<body id="sign-in">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 signin-form">
            <div class="panel-header">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center">
                            <img src="{{asset('images/logo_trans.png')}}" alt="Logo" style="width:100%;">
                        </h2>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form id="authentication" class="sign_validator" method="POST" action="{{ route('login2') }}">
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
                        <div class="col-md-12 text-center">
                            <input type="submit" value="Acceder" class="btn btn-default"/>
                        </div>
                        <div class="col-md-12">
                            <hr class="separator">
                        </div>
                        <div class="col-md-12 text-center">
                            <span>&#169; {{date('Y')}} 789.mx</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<!-- end of global js -->
<!-- begining of page level js -->
<!-- end of page level js -->
</body>

</html>
