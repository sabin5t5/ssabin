@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>App::isLocale('en') ? 'List' : 'लिस्ट' ,
    'panel'=> $panel,
    ])

    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead>
                    <tr>
                        <td colspan="7" style="padding-left:5px;">
                            @if($data['rows']->total() > $data['per_page'])
                                <div class="pull-right">

                                    <div class="dropdown"
                                         style="border: #ddd 2px solid; padding: 5px 10px; width: auto; height: auto; margin: 0px 0px 10px 0px; border-radius: 5px; color:#000; text-decoration: none;">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                                           aria-expanded="false">
                                            {{ $data['per_page'] }} <span class="pull-right"
                                                                          style="padding-left:10px;"><i
                                                        class="fi fi-arrow-down"></i></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            @for ($i=10; $i<=50; $i+=10)
                                                <li>
                                                    <a href="{{ route($base_route.'.index',['per_page'=>$i]) }}">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            <li><a href="{{ route($base_route.'.index',['per_page'=>$i]) }}">100</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @can('create-'.Illuminate\Support\Str::lower($panel))
                            <a type="button" href="{{ route($base_route.'.create') }}"
                               class="btn btn-success btn-sm ">
                                <i class="fi fi-plus"></i> New 
                            </a>
                            @endcan

                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>No of Users</th>
                        <th>No of Permission</th>
                        <th>
                            <i class="icon-time bigger-110 hidden-480"></i>
                            Created at
                        </th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody id="table-tbody">

                    @if ($data['rows']->count() > 0)
                        @foreach($data['rows'] as $row)

                            <tr>
                                <td>{{ $row->name }}</td>
                                <td>#</td>
                                <td>{{ $row->permissions()->count()}}</td>
                                <td><span class="nepaliDate" englishDate="{{ $row->created_at->format('Y-m-d') }}"></span></td>
                                <td>
                                    <div class="btn-group">
                                        @can('show-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                           class="btn btn-icon-only btn-success btn-sm row-show">
                                            <i class="fi fi-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.edit', $row->id) }}"
                                           class="btn btn-icon-only btn-info btn-sm row-edit">
                                            <i class="fi fi-pencil"></i>
                                        </a>
                                        @endcan
                                        <span>
                                            @can('delete-'.Illuminate\Support\Str::lower($panel))
                                            <button class="btn btn-icon-only btn-danger btn-sm confirm-delete"
                                                    attr="{{ route($base_route.'.destroy', $row->id) }}">
                                                <i class="fi fi-thrash"></i>
                                            </button>
                                            {{ html()->form('delete')->route($base_route.'.destroy',$row->id)->open() }}
                                            {{ html()->form()->close() }}
                                            @endcan
                                        </span>
                                    </div>



                                </td>
                            </tr>

                        @endforeach
                        <tr>
                            <td colspan="8">{!! $data['rows']->appends(request()->query())->links() !!}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8"><p>No data found.</p></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div><!-- /span -->
    </section>

@endsection

@section('js_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#form-filter-btn').click(function () {
                var url = '{{ route($base_route.'.index') }}';
                var name = $('input[name="filter_name"]').val();
                var status = $('select[name="filter_status"]').val();
                var flag = false;

                if (name) {
                    url = url + '?filter_name=' + name;
                    flag = true;
                }
                if (created_at) {
                    if (flag) {
                        url = url + '&filter_created_at=' + created_at;
                    } else {
                        url = url + '?filter_created_at=' + created_at;
                        flag = true;
                    }
                }

                if (created_at_bs) {
                    if (flag) {
                        url = url + '&filter_created_at_bs=' + created_at_bs;
                    } else {
                        url = url + '?filter_created_at_bs=' + created_at_bs;
                        flag = true;
                    }
                }
                if (status) {
                    if (flag) {
                        url = url + '&filter_status=' + status;
                    } else {
                        url = url + '?filter_status=' + status;
                        flag = true;
                    }
                }

                location.href = url;

            });

        });

    </script>
@endsection