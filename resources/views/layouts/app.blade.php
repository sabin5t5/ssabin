<?php 
    $site_info = App\Models\Admin\SiteConfig::pluck('config_values', 'config_keys');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sabin Sharma') }}</title>

    <!-- Styles -->
    <link href="{{ asset('sample1/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <br><br><br>
        <center>
            <a href="{{ url('/') }}">
                <table >
                    <tr>
                    <td><h6 style="color:#dd3333; font-size: 16px; padding-left: 5px; font-family: Verdana,Arial,sans-serif;">
                        <strong style="font-size:28px; font-family:'Brush Script MT', cursive;"> Sabin S</strong><br>
                        </h6></td>
                    </tr>
                </table>
            </a>
        </center>
        <br><br>
        @yield('content')
        <div class="row">
            <div class="col-sm-12">
                <hr>
                <p class="back-link" style="text-align: center">© Copyright {{ Carbon\Carbon::now()->format('Y')}} <a href="{{url('/')}}">sabinsharma.com.np</a>. All Right Reserved.</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
