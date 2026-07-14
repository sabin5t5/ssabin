@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'from' =>'site_config',
    'page'=> "Edit"
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        
            @include($view_path.'.includes.form', [
                'button' => 'Save','form_type'=>'edit'
            ])
        
    </section>

@endsection
