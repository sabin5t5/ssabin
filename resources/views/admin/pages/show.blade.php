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
                <table>
                    <tr>
                        <td>
                            <a href="{{ url('/admin/pages') }}" title="Back" type="button" class="btn btn-warning btn-sm"><i class="fi fi-go-back" aria-hidden="true"></i> Back</a>

                            @if($data['row']->deleted_at != null)
                            @can('restore-'.Illuminate\Support\Str::lower($panel))
                                <button class="btn btn-sm btn-success  confirm-restore"
                                        attr="#">
                                    <i class="fi fi-thrash"></i>Restore
                                </button>
                                {{ html()->form('POST')->route($base_route.'.restore',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}
                            @endcan
                            @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                                <button class="btn btn-sm btn-danger  confirm-force-delete"
                                        attr="{{ route($base_route.'.forcedelete', $data['row']->id) }}">
                                    <i class="fi fi-thrash"></i>Delete Permanently
                                </button>
                                {{ html()->form('POST')->route($base_route.'.forcedelete',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}
                            @endcan
                            @else
                            @can('update-'.Illuminate\Support\Str::lower($panel))
                                <a type="button" href="{{ route($base_route.'.edit', $data['row']->id) }}"
                                       class="btn btn-info btn-sm row-edit">
                                        <i class="fi fi-edit"></i> Edit
                                </a>
                            @endcan
                            <span>
                            @can('delete-'.Illuminate\Support\Str::lower($panel))
                                <button class="btn btn-danger btn-sm confirm-delete"
                                        attr="{{ route($base_route.'.destroy', $data['row']->id) }}">
                                    <i class="fi fi-thrash"></i>Delete
                                </button>
                                {{ html()->form('delete')->route($base_route.'.destroy',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}
                            @endcan
                            </span>
                            @endif
                        </td>
                    </tr>
                </table>
                <br>
                <div class="card card-default"> 
                    <div class="card-body">
                        <center><h3>{{ $data['row']->title }}</h3></center>
                        <small>
                            <strong>Author:</strong>  {{ $data['row']->username }} &nbsp; &nbsp; &nbsp;
                            <strong>Created Date:</strong>  {{ $data['row']->created_at->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;
                            <strong>Last Update Date:</strong>  {{ $data['row']->updated_at->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;
                            <strong>Status:</strong>  
                            @if($data['row']->status == 1)
                            <span class="label label-sm label-success"> Active </span>
                            @else
                                <span class="label label-sm label-warning"> InActive </span>
                            @endif
                            @if($data['row']->deleted_at != null)
                                <span class="label label-sm label-danger"> Deleted </span>
                            @endif
                            &nbsp; &nbsp; &nbsp;
                            </small>
                        <hr>
                        <p>{!! $data['row']->description !!}</p>
                        <hr>
                        <?php $pathinfo = pathinfo($data['row']->image_en); ?>
                        @if($data['row']->image_en  && ($pathinfo['extension']=='jpg' || $pathinfo['extension']=='JPG' || $pathinfo['extension']=='JPEG' || $pathinfo['extension']=='jpeg' || $pathinfo['extension']=='png'|| $pathinfo['extension']=='PNG' || $pathinfo['extension']=='gif' || $pathinfo['extension']=='GIF'))
                        <img src="{{ asset('images/pages/'.$data['row']->image_en)}}" class='img-responsive'  width="300">
                        @elseif($data['row']->image_en && ($pathinfo['extension']=='pdf'))
                            <iframe src="{{asset('/images/pages/'.$data['row']->image_en)}}" style="width:100%"></iframe>
                        @else
                        <a href="{{ asset('images/pages/'.$data['row']->image_en)}}" target="_blank">
                            File</a>
                        @endif


                        <table class='table table-stripped'>
                            <thead><tr><td colspan="2"> <center><strong>Meta Information</strong></center></td></tr></thead>
                            <tbody>
                                <tr>
                                    <td style="width:15%;"><strong>Meta Title:</strong></td>
                                    <td>{!! $data['row']->meta_title !!}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><strong>Meta Description:</strong></td>
                                    <td>{!! $data['row']->meta_description !!}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><strong>Meta Keywords:</strong></td>
                                    <td>{!! $data['row']->meta_keywords !!}</td>
                                </tr>
                            </tbody>
                        </table>

                            
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if(isset($data['activitylogs']))
                    @include('admin.includes.activity_lists')
                @endif
            </div>
        </div><!-- /.row -->
            
    </section><!-- /.main-content -->

@endsection