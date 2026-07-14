@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'from' =>'site_config',
    'page'=> "Edit"
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        {{ html()->form('POST')->route($base_route.'.update')->open() }}

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    {{ html()->label('Custom CSS', 'custom_css')->class('control-label') }}
                    {!! html()->textarea('custom_css', $data['rows']? $data['rows']->config_values:'')->class('form-control form-control-sm') !!}
                </div>
            </div>
        </div>
        @can('show-custom css')
        <div class="row">
            <div class="form-group mb-3">
                    <div class="col-md-12">
                        <button type="submit" name="submit"  class="btn btn-success">Update </button>
                    </div>
            </div>
        </div>
        @endcan
        {{ html()->form()->close() }}
    </section>

@endsection