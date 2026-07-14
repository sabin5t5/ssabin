@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb', [
       'base_route' => $base_route,
       'page' => 'Edit'
   ])

    <div id="content" class="padding-20">
        <div class="row">
            @include('admin.includes.flash-notification')
            <div class="col-md-12">

                    <!-- PAGE CONTENT BEGINS -->
                {{ html()->modelForm($data['row'], 'PUT')->route($base_route.'.update', $data['row']->id)->open() }}

                    {{ html()->hidden('id', $data['row']->id) }}


                    @include($view_path.'.includes.form', [
                    'button' => 'Update'
                    ])

                {{ html()->closeModelForm() }}

            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->

    @endsection
