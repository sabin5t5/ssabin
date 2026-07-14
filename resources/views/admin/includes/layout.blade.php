<!doctype html>
<html lang="en-US">
<meta charset="utf-8">
@include('admin.includes.head')
<!--
    .boxed = boxed version
-->
<body class="layout-admin aside-sticky header-sticky" data-s2t-class="btn-primary btn-sm bg-gradient-default rounded-circle b-0">


<!-- WRAPPER -->
<div id="wrapper" class="d-flex align-items-stretch flex-column">

    <!-- 
        HEADER 
        
        .header-match-aside-primary
    -->
    @include('admin.includes.header')

    <!-- /HEADER -->

    <div id="wrapper_content" class="d-flex flex-fill">

        <!-- HEADER -->
        @include('admin.includes.sidebar')
        <!-- /HEADER -->

        <!--MIDDLE-->
        <div id="middle" class="flex-fill">
           @yield('content')
        </div>
        <!-- /MIDDLE -->
    </div>
    @include('admin.includes.footer')
    @include('admin.includes.media_modal')
    @include('admin.includes.imagemodal')
    @include('admin.includes.pdfmodal')

</div>

<!-- JAVASCRIPT FILES -->
@include('admin.includes.scripts')
@yield('scripts')

</body>
</html>