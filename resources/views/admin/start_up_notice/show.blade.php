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
                            <a href="{{ url('/admin/start_up_notice') }}" title="Back" type="button" class="btn btn-warning btn-sm"><i class="fi fi-go-back" aria-hidden="true"></i> Back</a>
                            @if($data['row']->deleted_at != null)
                                @can('restore-'.Illuminate\Support\Str::lower($panel))
                                <span>
                                    <button class="btn btn-sm btn-success confirm-restore"
                                            attr="#">
                                        <i class="fi fi-go-back"></i>Restore
                                    </button>
                                    {{ html()->form('POST')->route($base_route.'.restore',$data['row']->id)->open() }}
                                    {{ html()->form()->close() }}
                                </span>

                                @endcan
                                @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                                <span>
                                    <button class="btn btn-sm btn-danger  confirm-force-delete"
                                            attr="{{ route($base_route.'.forcedelete', $data['row']->id) }}">
                                        <i class="fi fi-thrash"></i>Delete Permanently
                                    </button>
                                    {{ html()->form('POST')->route($base_route.'.forcedelete',$data['row']->id)->open() }}
                                    {{ html()->form()->close() }}
                                </span>
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
                                    <button class="btn btn-icon-only btn-danger btn-sm confirm-delete"
                                            attr="{{ route($base_route.'.destroy', $data['row']->id) }}">
                                        <i class="fi fi-thrash"></i>Delete
                                    </button>
                                    {{ html()->form('DELETE')->route($base_route.'.destroy',$data['row']->id)->open() }}
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
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#english" role="tab" aria-selected="true">
                                    English
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#nepali" role="tab"  aria-selected="false">
                                    Nepali
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div id="english"  class=" tab-pane fade show active" role="tabpanel" >

                                <center><h3>{{ $data['row']->title_en }}</h3></center>
                                <small>
                                    <strong>Author:</strong>  {{ $data['row']->username }} &nbsp; &nbsp; &nbsp;
                                    <strong>Published Date:</strong>  {{ $data['row']->published_from->format('Y-m-d') }} - {{ $data['row']->published_to->format('Y-m-d') }} &nbsp; &nbsp; &nbsp;
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
                                <p>{!! $data['row']->description_en !!}</p>

                            </div>
                            <div id="nepali"  class=" tab-pane fade" role="tabpanel" >

                               <center><h3>{{ $data['row']->title_np }}</h3></center>
                                <small>
                                    <strong>लेखक:</strong>  {{ $data['row']->username }} &nbsp; &nbsp; &nbsp;
                                    <strong>प्रकाशन मिति:</strong>  <span class="nepaliDate" englishDate="{{ $data['row']->published_from->format('Y-m-d') }}"></span> देखि <span class="nepaliDate" englishDate="{{ $data['row']->published_to->format('Y-m-d') }}"></span> सम्म &nbsp; &nbsp; &nbsp;
                                    <strong>{{ App::isLocale('en') ? 'Register Date' : 'दर्ता मिति' }}:</strong>  <span class="nepaliDate" englishDate="{{ $data['row']->created_at->format('Y-m-d') }}"></span> &nbsp; &nbsp; &nbsp;
                                    <strong>अपडेट मिति:</strong>  <span class="nepaliDate" englishDate="{{ $data['row']->updated_at->format('Y-m-d') }}"> </span>&nbsp; &nbsp; &nbsp;
                                    <strong>अवस्था:</strong>  
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
                                <p>{!! $data['row']->description_np !!}</p>
                            </div>
                        </div>
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
@section('js_scripts')
    <script src="{{ asset('admin/assets/scripts/ui-sweetalert.js') }}" type="text/javascript"></script>
@endsection