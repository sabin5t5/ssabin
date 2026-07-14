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
                            <a href="{{ url('/admin/blog-category') }}" title="Back" type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        @can('update-'.Illuminate\Support\Str::lower($panel))
                        <a type="button" href="{{ route($base_route.'.edit', $data['row']->id) }}"
                               class="btn btn-info btn-sm row-edit">
                                <i class="fa fa-edit"></i> Edit
                        </a>
                        @endcan
                        <span>
                        @can('delete-'.Illuminate\Support\Str::lower($panel))
                            <button class="btn btn-danger btn-sm confirm-delete"
                                    attr="{{ route($base_route.'.destroy', $data['row']->id) }}">
                                <i class="fa fa-trash"></i>Delete
                            </button>
                            {{ html()->form('delete')->route($base_route.'.destroy',$data['row']->id)->open() }}
                            {{ html()->form()->close() }}
                        @endcan
                        </span>
                        </td>
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
                            <td>Category Name</td>
                            <td>{{ $data['row']->category_name }}</td>
                        </tr>
                        <tr>
                            <td>Slug</td>
                            <td>{{$data['row']->slug}}</td>
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

