@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=> "Create"
    ])
    <div id="content" class="padding-20">
			@include('admin.includes.flash-notification')
	        <div class="col-md-12">
			{{ html()->form('POST')->route($base_route.'.store')->open() }}

					@include($view_path.'.includes.form', [
                    'button' => 'Save'
                ])

	        {{ html()->form()->close() }}

	        </div>
    </div>

@endsection
@section('js_scripts')
    @yield('post_scripts')
@endsection