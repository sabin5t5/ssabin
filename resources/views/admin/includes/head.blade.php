<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'SSF CMS') }}</title>

    <meta name="viewport" content="width=device-width, maximum-scale=5, initial-scale=1, user-scalable=0">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

    <!-- up to 10% speed up for external res -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com/">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <!-- preloading icon font is helping to speed up a little bit -->
    <link rel="preload" href="{{ asset('admin/assets/fonts/flaticon/Flaticon.woff2') }}" as="font"
        type="font/woff2" crossorigin>

    <!-- non block rendering : page speed : js = polyfill for old browsers missing `preload` -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/core.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendor_bundle.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap">

    <!-- <link type="stylesheet" href="{{ ViewHelper::getAssetPath('select2/css/select2.min.css', 'plugins') }}"> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" href="demo.files/logo/icon_512x512.png">

    <link rel="manifest" href="{{ asset('admin/assets/images/manifest/manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('/packages/nepaliDatePicker/css/nepali.datepicker.v4.0.1.min.css') }}">
    
    
    <link type="stylesheet" href="{{ ViewHelper::getAssetPath('ckeditor/contents.css', 'plugins') }}">

    <link rel="stylesheet" href="{{ ViewHelper::getAssetPath('Croppie-2.4.1/croppie.css', 'plugins') }}">
    <link href="{{ ViewHelper::getAssetPath('dropzone/css/dropzone.css', 'plugins') }}" rel="stylesheet" type="text/css" />

    <meta name="theme-color" content="#377dff">
    <meta name="public_path" content="{{ url('/') }}">
    <meta name="locale" content="np">
    <style>
        body.layout-admin #middle {
            /*padding: 30px 10px;*/
        }

        body.layout-admin #middle>.page-title {
            padding: 3px 23px;
            background-color: #fff;
            margin: -30px -30px 0px !important;
            border-top: 1px solid #e9ecef;
            z-index: 2;
        }

        body.layout-admin #middle section {
            font-size: 0.90rem !important
        }

        .btn>i {
            margin-right: 0px !important
        }

        @media only screen and (min-width: 992px) {
            nav.navbar:not(.h-auto) {
                min-height: 55px;
            }
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #dde4ea;
            border-radius: .2rem;
            height: 43px;
        }

        /* image add/edit image size*/
        .small-image {
            width: 85%;
        }

        .hidden {
            display: none;
        }

        div.image-preview {
            position: relative;
            float: left;
        }

        a.removebtn {
            position: absolute;
            float: right;
            top: 0%;
            right: 15%;
        }

        a.delete_image_btn {
            position: relative;
            bottom: 220px;
            float: right;
            right: 31px;
        }

        input[type="file"] {
            cursor: pointer;
        }

        button:focus {
            outline: 0;
        }

        .file-btn {
            position: relative;
        }

        .file-btn input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: #fff;
            opacity: 1;
        }

        .gallery-container {
            overflow-y: scroll;
            height: 60vh;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            /* Compensate for excess margin on outer gallery flex items */
            margin: -1rem -1rem;
            height: 200px;
        }

        .gallery-item {
            flex: 1 0 12rem;
            margin: 1rem;
            box-shadow: 0.3rem 0.4rem 0.4rem rgba(0, 0, 0, 0.4);
            overflow: hidden;
            padding: 0.5rem;
        }

        .gallery-image-active {
            background-color: #28a745;
            padding: 0.3rem;
        }

        .gallery-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 400ms ease-out;
        }

        @supports (display: grid) {
            .gallery {
                /* display: grid; */
                grid-template-columns: repeat(5, minmax(0, 1fr));
                grid-gap: 2rem;
            }

            .gallery,
            .gallery-item {
                margin: 0;
            }
        }

        #mediaModal .modal-dialog.modal-lg {
            max-width: 80% !important;
        }

        .preview-container img {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            padding: 10px;
        }

        .nepali-date-picker .drop-down-content {
            padding: 0px;
        }

        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 0 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
            margin-top: 15px !important;
            background: #d6dfe799;
        }

        fieldset fieldset.scheduler-border {
            padding: 0.4rem 1.4em 1.4em 1.4em !important;
        }

        legend.scheduler-border {
            font-size: 1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
            background-color: #006AA8;
            color: #eff8fe;
            border-radius: 3px;

        }

        .form_row_margin_bottom {
            margin-bottom: -20px;
            margin-top: -10px;
        }

        label {
            font-weight: 800;
        }

        .form-control-sm {
            font-size: 0.9rem;
            line-height: 1.5;
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            font-size: 0.9rem;
        }

        .select2-container .select2-selection--single,
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }

        .select2-container {
            width: 100% !important;
        }

        .btn-sm {
            line-height: 1.2rem;
        }

        .form-control {
            border: 1px solid #aaaaaa;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        .nepali-date-picker {
            font-size: 0.8rem;
        }

        .nepali-date-picker .drop-down-content {
            padding: 5px 0px 5px 5px;

        }

        .drop-down-content li {
            text-align: center;
        }

        .nav-tabs .nav-link {
            background: #006AA8;
            color: aliceblue;
            font-size: 14px !important;
            font-weight: 800 !important;
            border-color: aliceblue !important;
        }

        .nav-tabs .nav-link.active {
            background: #006AA8;
            color: aliceblue;

            border-color: aliceblue !important;
        }

        .inactive {
            color: #214c73 !important;
            background-color: #fc905bad !important;
            border-color: aliceblue !important;
            font-size: 14px !important;
            font-weight: 800 !important;
        }

        legend a {
            color: white;
        }

        .form-checkbox {
            margin-top: 30px;
        }

        @media print {
            .no_print {
                display: none !important;
            }
        }
    </style>
    @yield('scripts')

</head>
