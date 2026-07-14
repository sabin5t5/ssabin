@extends('admin.includes.layout')


@section('content')

    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page' => 'Edit'
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="col-md-12">
            <!-- PAGE CONTENT BEGINS -->
            {{ html()->modelForm($data['row'], 'PUT')->route($base_route.'.update', $data['row']->id)->acceptsFiles()->open() }}


                {{ html()->hidden('id', $data['row']->id) }}


                @include($view_path.'.includes.form', [
                    'button' => 'Update'
                ])

                    
            {{ html()->closeModelForm() }}


        </div><!-- /.page-content -->
    </section><!-- /.main-content -->

 @endsection
@section('js_scripts')
    @yield('post_scripts')
@endsection
