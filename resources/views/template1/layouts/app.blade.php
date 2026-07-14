<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="title" content="Sabin Sharma | Software Developer | Web Developer | Web Designer | Graphics Designer  | From Pokhara Nepal | Lives in Monroe, NC, USA | Laravel Developer | IT Counseller">
	<meta name="description" content="Web Developer From Pokhara Nepal who lives in USA and like to play with computer and have interest in developing and designing web application.">
	<meta name="keywords" content="sabin,sharma, web, developer, designer, programmer, pokhara, nepal, Monroe, NC, USA, software, laravel, php, contact, skills, about, gandaki, Sabin Shamra">
	<meta property="og:title" content="Sabin Sharma | Software Developer | Web Developer | Web Designer | Graphics Designer  | IT Counseller | Pokhara Nepal | Lives in  Monroe, NC, USA">
	<meta property="og:site_name" content="Sabinsharma.com.np">
	<meta property="og:image" content="assets/images/home.jpg">
	<meta name="google-site-verification" content="z7HodD1EEo4ZwUQb4QaieJNE0xy0owIEOnPz8DnJpVQ">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script type="text/javascript" async="" src="https://www.googletagmanager.com/gtag/js?id=G-TD15SXZFHR&amp;cx=c&amp;gtm=4e6321h2"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-89252826-3"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-89252826-3');
	</script>

	<title>Sabin Sharma | Software Developer | Web Developer | From Pokhara Nepal | Lives in Monroe, NC, USA | Portfolio</title>
	<link rel="icon" type="image/icon" href="assets/images/logo.png">

	<link rel="icon" href="assets/images/logo.png">
	<!-- Favicons -->
	<link href="{{ asset(config('custom.front_template').'assets/img/favicon.png')}}" rel="icon">
	<link href="asset(config('custom.front_template').'assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com" rel="preconnect">
	<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="{{ asset(config('custom.front_template').'/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{ asset(config('custom.front_template').'/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
	<link href="{{ asset(config('custom.front_template').'/vendor/aos/aos.css')}}" rel="stylesheet">
	<link href="{{ asset(config('custom.front_template').'/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
	<link href="{{ asset(config('custom.front_template').'/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

	<!-- Main CSS File -->
	<link href="{{ asset(config('custom.front_template').'/css/main.css')}}" rel="stylesheet">

	<!-- =======================================================
	* Template Name: SnapFolio
	* Template URL: https://bootstrapmade.com/snapfolio-bootstrap-portfolio-template/
	* Updated: Jul 21 2025 with Bootstrap v5.3.7
	* Author: BootstrapMade.com
	* License: https://bootstrapmade.com/license/
	======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header dark-background d-flex flex-column justify-content-center">
    @include(config('custom.front_template').'.includes.head')

  </header>

  <main class="main">
    @yield('content')
  </main>

  <footer id="footer" class="footer position-relative">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">SabinSharma.com</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> | <a href="https://bootstrapmade.com/tools/">DevTools</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset(config('custom.front_template').'/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/aos/aos.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/typed.js/typed.umd.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/waypoints/noframework.waypoints.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{ asset(config('custom.front_template').'/vendor/swiper/swiper-bundle.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{ asset(config('custom.front_template').'/js/main.js')}}"></script>

</body>

</html>