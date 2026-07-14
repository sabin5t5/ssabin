@extends('admin.includes.layout')

@section('content')

    @include('admin.includes.breadcrumb', [
       'base_route' => $base_route,
       'page' => 'View'
   ])

   <section class="rounded mb-3">
        <div class="row">
            <div class="col-md-8">

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <table>
                            <tr>
                                <td>
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
                                    <td>Full Name</td>
                                    <td>{{ $data['row']->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{$data['row']->email}}</td>
                                </tr>
                                <tr>
                                    <td>Roles</td>
                                    <td>
                                       @foreach($data['row']->roles()->get() as $role)

                                            <a href="{{ route('admin.roles.show', ['role'=>$role->id] )}}"><span class="badge badge-primary"> {{ Illuminate\Support\Str::ucfirst($role->name) }}</span></a>

                                        @endforeach
                                    </td>
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
                                    <td>
                                        @if($data['row']->status == 1)
                                            <span class="label label-sm label-success"> Active </span>
                                        @else
                                            <span class="label label-sm label-warning"> InActive </span>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->

                    </div><!-- /.col -->
                </div><!-- /.row -->
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