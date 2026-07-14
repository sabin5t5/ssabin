@extends('admin.includes.layout')

@section('content')


<style>
.tooltipclipboard {
  position: relative;
  display: inline-block;
}

.tooltipclipboard .tooltipclipboardtext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltipclipboard .tooltipclipboardtext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltipclipboard:hover .tooltipclipboardtext {
  visibility: visible;
  opacity: 1;
}
</style>

    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page' => 'Edit'
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="col-md-12">
            <!-- PAGE CONTENT BEGINS -->
           


                @include($view_path.'.includes.form', [
                    'button' => 'Update'
                ])


            


        </div><!-- /.page-content -->
    </div><!-- /.main-content -->

 @endsection
@section('js_scripts')
    @yield('post_scripts')
    <script>
function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  
  var tooltipclipboard = document.getElementById("myTooltip");
  tooltipclipboard.innerHTML = "Copied: " + copyText.value;
}

function outFunc() {
  var tooltipclipboard = document.getElementById("myTooltip");
  tooltipclipboard.innerHTML = "Copy to clipboard";
}
</script>
@endsection
