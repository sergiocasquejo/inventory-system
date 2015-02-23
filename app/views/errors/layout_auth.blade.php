<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>500</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ assets('/admin/backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ assets('/admin/backend/css/bootstrap-reset.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ assets('/admin/backend/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ assets('/admin/backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ assets('/admin/backend/css/style-responsive.css') }}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
  <!-- body-500, body-404 -->
  <body class="body-500">

    <div class="container">

      <section class="error-wrapper">
          @yield('content')
      </section>

    </div>


  </body>
</html>
