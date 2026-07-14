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
                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="20%">Column</th>
                            <th>Value</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{ $data['row']->name }}</td>
                        </tr>
                        <tr>
                            <td>No of Users </td>
                            <td>{{ $data['row']->users()->count()}}</td>
                        </tr>
                        <tr>
                            <td>Permissions</td>
                            <td>
                                <!-- {{ $data['row']->permissions()->get() }} -->

                                <table class="table table-striped">
                                    <tr>
                                        <td>Panel</td>
                                        <td>Permission</td>
                                    </tr>
                                    @foreach($data['permission_lists'] as $p_list)
                                    <tr>
                                        <td>{{ Illuminate\Support\Str::ucfirst($p_list['panel_name'])  }}</td>
                                        <td>
                                            @foreach($p_list['permissions'] as $permission)
                                                <span class="badge badge-primary"> {{ Illuminate\Support\Str::ucfirst($permission['action_name']) }}</span>

                                            @endforeach

                                        </td>
                                    </tr>
                                    @endforeach

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Created At</td>
                            <td>{{$data['row']->created_at}}</td>
                        </tr>
                        <tr>
                            <td>Updated_at</td>
                            <td>{{$data['row']->updated_at}}</td>
                        </tr>
                    </tbody>
                </table>
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
@endsection