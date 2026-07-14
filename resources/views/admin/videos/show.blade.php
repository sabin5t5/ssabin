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
                        @if($data['row']->deleted_at != null)
                            @can('restore-'.Illuminate\Support\Str::lower($panel))
                            <td>
                                <button class="btn btn-sm btn-success  confirm-restore"
                                        attr="#">
                                    <i class="fi fi-thrash"></i>Restore
                                </button>
                                {{ html()->form('POST')->route($base_route.'.restore',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}

                            </td>
                            @endcan
                            @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                            <td>
                                <button class="btn btn-sm btn-danger  confirm-force-delete"
                                        attr="{{ route($base_route.'.forcedelete', $data['row']->id) }}">
                                    <i class="fi fi-thrash"></i>Delete Permanently
                                </button>
                                {{ html()->form('POST')->route($base_route.'.forcedelete',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}
                            </td>
                            @endcan
                        @else
                            <td>
                            @can('update-'.Illuminate\Support\Str::lower($panel))
                                <a type="button" href="{{ route($base_route.'.edit', $data['row']->id) }}"
                                       class="btn btn-info btn-sm row-edit">
                                        <i class="fi fi-pencil"></i> Edit
                                </a>
                            @endcan
                            <span>
                            @can('update-'.Illuminate\Support\Str::lower($panel))
                                @if ($data['row']->isDeletable())
                                        <button class="btn btn-danger btn-sm confirm-delete"
                                                attr="{{ route($base_route.'.destroy', $data['row']->id) }}">
                                            <i class="fi fi-thrash"></i>Delete
                                        </button>
                                    @endif
                                {{ html()->form('delete')->route($base_route.'.destroy',$data['row']->id)->open() }}
                                {{ html()->form()->close() }}
                            @endcan
                            </span>
                            </td>
                        @endif
                    </tr>
                </table>
                <br>
                <div class="table-responsive">
                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="20%">Column</th>
                            <th width="40%">Value</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>Id</td>
                            <td>{{ $data['row']->id }}</td>
                        </tr>
                        <tr>
                            <td>Videos Links English</td>
                            <td>{{ $data['row']->title_en }}</td>
                        </tr>
                        <tr>
                            <td>Videos Links English</td>
                            <td>{{ $data['row']->title_np }}</td>
                        </tr>
                        <tr>
                            <td>Slug</td>
                            <td>{{$data['row']->slug}}</td>
                        </tr>
                        <tr>
                            <td> Videos Link</td>
                            <td>{{$data['row']->videos_link}}</td>
                        </tr>
                        <tr>
                            <td>Created Date</td>
                            <td>{{$data['row']->created_at }}</td>
                        </tr>
                        <tr>
                            <td>Last Updated Date</td>
                            <td>{{$data['row']->updated_at}}</td>
                        </tr>
                        <tr>
                            <td>Current Status</td>
                            <td>@if($data['row']->deleted_at != null)
                                    <span class="badge badge-sm badge-danger"> Deleted </span>
                                @elseif($data['row']->status == 1)
                                    <span class="badge badge-sm badge-success"> Active </span>
                                @else
                                    <span class="badge badge-sm badge-warning"> InActive </span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->

            </div><!-- /.col -->
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