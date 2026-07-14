<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Social Security Fund') }}</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            a {
                color: #000;
                padding: 0 25px;
                font-size: 12px;
                text-align: 
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .fourofour {
            font-size: 16em;
            line-height: 1;
            clear: both;
            text-align: center;
            background-repeat: no-repeat;
            background-position: center, bottom;
            overflow: hidden;
            height: 300px;
            font-style: bold;
        }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <center><h1>Unauthorized Access</h1></center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <article class="page type-page status-publish hentry">

                            <div class="entry-content">
                                <div class="fourofour" style="background-image:url('{{asset('images/logo/notfound.jpg')}}');">
                                    403
                                </div>
                                <div class="page-content text-center">
                                    <center><p><strong>Sorry, You are not allow to enter here.</strong></p> <a href="{{ url('/') }}"><strong><i> Go to Home Page</i></strong></a></center>
                                </div>
                            </div><!-- .entry-content -->
                        </article><!-- #post -->
                    </div>
                           
                </div>
            </div>
        </div>
    </body>
</html>
