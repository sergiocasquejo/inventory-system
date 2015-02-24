<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>FlatLab - Flat & Responsive Bootstrap Admin Template</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/assets/backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/backend/css/bootstrap-reset.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('/assets/backend/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('/assets/backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/backend/css/style-responsive.css') }}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/assets/backend/js/html5shiv.js') }}"></script>
    <script src="{{ asset('/assets/backend/js/respond.min.js') }}"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container">
        <form role="form" method="POST" action="{{{ URL::to('/users/login') }}}" accept-charset="UTF-8" class="form-signin">
        <h2 class="form-signin-heading">sign in now</h2>
        <div class="login-wrap">
            <input class="form-control" tabindex="1" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">

            <input class="form-control" tabindex="2" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
            <label class="checkbox">
                <input tabindex="4" type="checkbox" name="remember" id="remember" value="1"> Remember me
                <span class="pull-right"> <a href="{{{ URL::to('/users/forgot_password') }}}"> Forgot Password?</a></span>
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
            @include('admin/_partials/messages')
        </div>

      </form>

    </div>


  </body>
</html>
