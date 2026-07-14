@extends('admin.includes.layout')

@section('content')

        @include('admin.includes.breadcrumb', [
           'base_route' => $base_route,
           'page' => 'View'
       ])

    <section class="rounded mb-3">
        <div class="row">
            <div class="col-md-8">
                <!-- PAGE CONTENT BEGINS -->
                <a href="{{ url('/admin/blogs') }}" title="Back" type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

                @if($data['row']->deleted_at != null)
                    @can('restore-'.Illuminate\Support\Str::lower($panel))
                    <span>
                        <button class="btn btn-sm btn-success confirm-restore"
                                attr="#">
                            <i class="fa fa-refresh"></i>Restore
                        </button>
                        {{ html()->form('POST')->route($base_route.'.restore',$data['row']->id)->open() }}
                        {{ html()->form()->close() }}
                    </span>

                    @endcan
                    @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                    <span>
                        <button class="btn btn-sm btn-danger  confirm-force-delete"
                                attr="{{ route($base_route.'.forcedelete', $data['row']->id) }}">
                            <i class="fa fa-trash"></i>Delete Permanently
                        </button>
                        {{ html()->form('POST')->route($base_route.'.forcedelete',$data['row']->id)->open() }}
                        {{ html()->form()->close() }}
                    </span>
                    @endcan
                @else
                    @can('update-'.Illuminate\Support\Str::lower($panel))
                    <a type="button" href="{{ route($base_route.'.edit', $data['row']->id) }}"
                           class="btn btn-info btn-sm row-edit">
                            <i class="fa fa-edit"></i> Edit
                    </a>
                    @endcan
                    <span>
                    @can('delete-'.Illuminate\Support\Str::lower($panel))
                        <button class="btn btn-icon-only btn-danger btn-sm confirm-delete"
                                attr="{{ route($base_route.'.destroy', $data['row']->id) }}">
                            <i class="fi fi-thrash"></i>Delete
                        </button>
                        {{ html()->form('DELETE')->route($base_route.'.destroy',$data['row']->id)->open() }}
                        {{ html()->form()->close() }}
                        @endcan
                    </span>
                @endif      
                <hr>
                <div class="card card-default"> 
                    <div class="card-body">

                        <center><h3>{{ $data['row']->title }}</h3></center>
                        <small>
                            <strong>Author:</strong>  {{ $data['row']->username }} &nbsp; &nbsp; &nbsp;
                            <strong>Published Date:</strong>  {{ $data['row']->published_at->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;
                            <strong>Created Date:</strong>  {{ $data['row']->created_at->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;

                            <strong>Last Update Date:</strong>  {{ $data['row']->updated_at->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;
                            <strong>Status:</strong>  
                            @if($data['row']->status == 1)
                            <span class="label label-sm label-success"> Active </span>
                            @else
                                <span class="label label-sm label-warning"> InActive </span>
                            @endif
                            &nbsp; &nbsp; &nbsp;
                            @if($data['row']->is_highlight == 1)
                            <span class="label label-sm label-info"> <strong>Highlight</strong>: Yes </span>
                            @endif
                            @if($data['row']->deleted_at != null)
                                <span class="label label-sm label-danger"> Deleted </span>
                            @endif
                            &nbsp; &nbsp; &nbsp;
                            </small>
                        <hr>
                        <p>{!! $data['row']->description !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if(isset($data['activitylogs']))
                    @include('admin.includes.activity_lists')
                @endif
            </div>
                    
        </div><!-- /.page-content -->
    </section><!-- /.main-content -->

@endsection
@section('js_scripts')
@endsection