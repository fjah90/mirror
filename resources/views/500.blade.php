<!DOCTYPE html>
<html>

<head>
    <title>500 | Clean Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- global level css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- end of global css-->
    <!-- page level styles-->
    <link href="{{asset('assets/css/pages/error_pages.css')}}" rel="stylesheet" type="text/css"/>
    <!-- end of page level styles-->
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <div class="error_content">
                <div class="text-center">
                    <h2>
                        <img src="{{asset('assets/img/logo_blue.png')}}" alt="Logo">
                    </h2>
                </div>
                <div class="text-center">
                    <div class="error">
                        <span class="folded-corner"></span>
                        <p class="type">500</p>
                        <p class="type-text">Internal Server Down</p>
                        <p class="message">Hold on! ,Something went wrong at our end. Mean while try <a href="index" >Home</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
</body>
</html>
