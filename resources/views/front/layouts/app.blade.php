<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="{{ asset('inc/js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('inc/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('inc/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <link href="{{ asset('inc/js/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inc/js/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inc/js/plugins/gijgo/gijgo.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Other Styles -->
    @yield('styles')
    <!-- End Styles -->
</head>
<body>
    <div id="app">

      @include('front.components.main.menu')

      @include('front.components.main.alerts')

        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        <div class="footerHeader" ></div>
        <div class="container row" id="footer">

    		<div class="col-md-4">
    		    <h3>About us</h3>
    		    <p>
    		        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    		    </p>
    		</div>
    		<div class="col-md-4">
    		    <h3>Contact Us</h3>
    		    <ul>
    		        <li>Phone : 123 - 456 - 789</li>
    		        <li>E-mail : info@comapyn.com</li>
    		        <li>Fax : 123 - 456 - 789</li>
    		    </ul>
    		    <p>
    		        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
    		    </p>
    		    <ul class="sm">
    		        <li><a href="#" ><img src="https://www.facebook.com/images/fb_icon_325x325.png" class="img-responsive"></a></li>
    		        <li><a href="#" ><img src="https://lh3.googleusercontent.com/00APBMVQh3yraN704gKCeM63KzeQ-zHUi5wK6E9TjRQ26McyqYBt-zy__4i8GXDAfeys=w300" class="img-responsive" ></a></li>
    		        <li><a href="#" ><img src="http://playbookathlete.com/wp-content/uploads/2016/10/twitter-logo-4.png" class="img-responsive"  ></a></li>
    		    </ul>
    		</div>
        </div>
    </footer>

    <script src="{{ asset('inc/js/hp.js') }}" type="text/javascript"></script>

    <script src="{{ asset('inc/js/plugins/select2/select2.min.js') }}" type="text/javascript"></script>

</body>
</html>
