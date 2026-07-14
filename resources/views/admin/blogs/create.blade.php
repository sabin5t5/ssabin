@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=> "Create"
    ])
    <section class="rounded mb-3">
			@include('admin.includes.flash-notification')
	        <div class="col-md-12">
				{{ html()->form('POST')->route($base_route.'.store')->acceptsFiles()->open() }}

					@include($view_path.'.includes.form', [
					'button' => 'Save'
					])

				{{ html()->form()->close() }}
	        </div>
    </section>
@endsection
@section('js_scripts')
	@yield('post_scripts')
@endsection
