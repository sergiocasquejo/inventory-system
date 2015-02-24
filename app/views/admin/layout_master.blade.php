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
    <link href="{{ asset('/assets/backend/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('/assets/backend/css/owl.carousel.css') }}" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{ asset('/assets/backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/backend/css/style-responsive.css') }}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ asset('/assets/backend/js/html5shiv.js') }}"></script>
      <script src="{{ asset('/assets/backend/js/respond.min.js') }}"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" class="">
      @include('admin/_partials/header')
      @include('admin/_partials/sidebar')
      
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
            @include('admin/_partials/messages')
            @yield('content')
          </section>
      </section>
      <!--main content end-->
  </section>

  <!-- js placed at the end of the document so the pages load faster -->
  <script src="{{ asset('/assets/backend/js/jquery.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/jquery-1.8.3.min.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/jquery.scrollTo.min.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
  <script src="{{ asset('/assets/backend/js/jquery.sparkline.js') }}" type="text/javascript"></script>
  <script src="{{ asset('/assets/backend/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/owl.carousel.js') }}" ></script>
  <script src="{{ asset('/assets/backend/js/jquery.customSelect.min.js') }}" ></script>

  <!--common script for all pages-->
  <script src="{{ asset('/assets/backend/js/common-scripts.js') }}"></script>

  <!--script for this page-->
  <script src="{{ asset('/assets/backend/js/sparkline-chart.js') }}"></script>
  <script src="{{ asset('/assets/backend/js/easy-pie-chart.js') }}"></script>

  <script>
      //owl carousel
      $(document).ready(function() {
          $("#owl-demo").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true

          });
      });

      //custom select box
      $(function(){
          $('select.styled').customSelect();
      });

  </script>

  </body>
</html>
