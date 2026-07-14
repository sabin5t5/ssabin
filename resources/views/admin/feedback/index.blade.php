@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>'List',
    'panel'=> $panel,
    ])

    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="row">            
            <div class="col-md-12">
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                @if($data['is_trash'] == false)
                    @if (Auth::user()->can('update-'.Illuminate\Support\Str::lower($panel)) && $data['rows']->count() > 0)
                        <label class="mt-checkbox mt-checkbox-outline btn btn-sm  float-left ">
                            <input type="checkbox" id="checkAll">
                        </label>
                        <div class="dropdown show float-left">
                            <a class="btn border-info btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bulk Action <i class="fi fi-arrow-down-full "></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @foreach($bulk_action as $key => $value)
                                    <a class="dropdown-item bulk_list" id="{{ $key }}">{{ $value }}</a>
                                @endforeach
                            </div>
                            {{ html()->form('POST')->route($base_route.'.bulk-action')->id('bulk-action-form')->class('display:none')->open() }}
                                {{ html()->hidden('row_ids')->class('row_ids') }}
                                {{ html()->hidden('bulk_action')->class('bulk_action') }}
                            {{ html()->form()->close() }}
                        </div>
                        
                    @endif
                    @canany(['restore-'.Illuminate\Support\Str::lower($panel), 'forceDelete-'.Illuminate\Support\Str::lower($panel)])
                    <a type="button"
                       href="{{ route($base_route.'.index', ['data-show'=>'trashed']) }}"
                       class="btn btn-danger btn-sm float-right" data-toggle="tooltip" title="Deleted Records">
                        <i class="fa fa-trash"></i>Trash List
                    </a>
                    @endcanany
        
                    @if($data['rows']->total() > $data['per_page'])
                        <div class="dropdown show float-right">
                            <a class="btn border-info btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $data['per_page'] }} <i class="fi fi-arrow-down-full"></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @for ($i=10; $i<=50; $i+=10)
                                    <a class="dropdown-item" href="{{ route($base_route.'.index',['per_page'=>$i]) }}">{{ $i }}</a>
                                @endfor
                            </div>
                        </div>
                    @endif  
                @else
                    @can('show-'.Illuminate\Support\Str::lower($panel))
                    <div class="btn-group float-right">
                        <a type="button" href="{{ route($base_route.'.index') }}"
                           class="btn btn-success btn-sm " data-toggle="tooltip" title="Active Records">
                            <i class="fa fa-refresh"></i>Active Feedback Lists
                        </a>
                    </div>
                    @endcan
                @endif
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                        <tr>
                            @if($data['is_trash'] == false)
                            <th></th>
                            @endif
                            <th class="bb-0 font-weight-bold fs--14 min-w-300">Name</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-300">Email</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100">Phone</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Type</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Status</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100"> Received At</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Action</th>
                        </tr>
                        @if($data['is_trash'] == false)
                        <tr>
                            <td></td>
                            <td>{{ html()->text('filter_name', isset($data['request']['filter_name'])?$data['request']['filter_name']:null)->placeholder('Enter Name')->class('form-control form-control-sm') }}</td>
                            <td>{{ html()->text('filter_email', isset($data['request']['filter_email'])?$data['request']['filter_email']:null)->placeholder('Enter Email')->class('form-control form-control-sm') }}</td>
                            <td>{{ html()->text('filter_phone', isset($data['request']['filter_phone'])?$data['request']['filter_phone']:null)->placeholder('Enter Phone')->class('form-control form-control-sm') }}</td>

                            <td>
                                {!! html()->select('filter_type', $data['types'], isset($data['request']['filter_type'])?$data['request']['filter_type']:null )->class('form-control form-control-sm') !!}
                            </td>
                            <td>{!! html()->select('filter_status', ['' => 'All', 'new' => 'New', 'replied' => 'Replied'], isset($data['request']['filter_status'])?$data['request']['filter_status']:null )->class('form-control form-control-sm') !!}</td>
                            <td>
                                <div class="input-group mb-3">
                                    {!! html()->text('filter_created_at_bs', isset($data['request']['filter_created_at_bs'])?$data['request']['filter_created_at_bs']:null)->placeholder('Received Date')->class('form-control form-control-sm nepalidate-picker')->id('filter_created_at_bs') !!}
                                    <div class="input-group-append">
                                        <button class="btn btn-danger btn-sm" type="button" id="filter_created_at_clear"><i class="fi fi-close"></i></button>
                                    </div>

                                    {!! html()->text('filter_created_at', isset($data['request']['filter_created_at'])?$data['request']['filter_created_at']:null)->class('hidden')->id('filter_created_at') !!}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-warning btn-sm" id="form-filter-btn">
                                        <i class="fi fi-play"></i>
                                    </button>
                                    <a href="{{ route($base_route.'.index') }}" class="btn btn-dark btn-sm">
                                        <i class="fi fi-reload"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>
                        @endif
                        </thead>


                        <tbody id="item_list">
                        @if ($data['rows']->count() > 0)

                            @foreach($data['rows'] as $row)

                                <tr class="odd gradeX">
                                    @if($data['is_trash'] == false)
                                    <td class="center">
                                        @can('update-'.Illuminate\Support\Str::lower($panel))
                                        <label>
                                            <input type="checkbox" class="checkboxes" value="{{ $row->id }}"/>
                                        </label>
                                        @endcan
                                    </td>
                                    @endif
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ $row->phone }}</td>
                                    <td>{{ config('custom.feedbackTypes.'.$row->type.'.name_np') }}</td>
                                    
                                    <td>
                                        @if($row->status == 1)
                                            <span class="badge badge-sm badge-danger"> New </span>
                                        @else
                                            <span class="badge badge-sm badge-success"> Replied </span>
                                        @endif
                                        @if($row->deleted_at != null)
                                            <span class="badge badge-sm badge-danger"> Deleted </span>
                                        @endif
                                    </td>
                                    <td> <span class="nepaliDate" englishDate="{{ $row->created_at->format('Y-m-d') }}"></span></td>
                                    @if($data['is_trash'] ==true)
                                    <td>
                                        <div class="btn-group">
                                            @can('show-'.Illuminate\Support\Str::lower($panel))
                                            <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                               class="btn btn-icon-only btn-success btn-sm row-show">
                                                <i class="fa fa-eye"></i>Show
                                            </a>
                                            @endcan
                                            @can('restore-'.Illuminate\Support\Str::lower($panel))
                                            <span>
                                                <button class="btn btn-sm btn-warning  confirm-restore"
                                                        attr="#" data-toggle="tooltip" title="Restore">
                                                    <i class="fa fa-refresh"></i>Restore
                                                </button>
                                                {{ html()->form('POST')->route($base_route.'.restore',$row->id)->open() }}
                                                {{ html()->form()->close() }}
                                            </span>
                                            @endcan
                                            @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                                            <span>
                                                <button class="btn btn-sm btn-danger  confirm-force-delete"
                                                        attr="{{ route($base_route.'.forcedelete', $row->id) }}"
                                                        data-toggle="tooltip" title="Delete Permanently">
                                                    <i class="fa fa-times"></i>Delete 
                                                </button>
                                                {{ html()->form('POST')->route($base_route.'.forcedelete',$row->id)->open() }}
                                                {{ html()->form()->close() }}
                                            </span>
                                            @endcan
                                        </div>
                                    </td>
                                    @else
                                    <td>
                                        <div class="btn-group">
                                            @can('show-'.Illuminate\Support\Str::lower($panel))
                                            <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                               class="btn btn-icon-only btn-success btn-sm row-show">
                                                <i class="fi fi-eye"></i>
                                            </a>
                                            @endcan
                                            @can('delete-'.Illuminate\Support\Str::lower($panel))
                                            <button class="btn btn-icon-only btn-danger btn-sm confirm-delete-button" value = "{{$row->id}}">
                                                <i class="fi fi-thrash"></i> 
                                            </button>
                                            @endcan         
                                        </div>
                                    </td>
                                    @endif
                                </tr>

                            @endforeach
                            <tr>
                                <td colspan="9">
                                    <span style="margin: 10px 0px; display: block; text-align: left; float: left; line-height: 35px;"> Showing {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+1 }}
                                        to {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+$data['rows']->count() }}
                                        of {{ $data['rows']->total() }} entries</span>
                                    <span class="float-right">{!! $data['rows']->appends(request()->query())->links() !!}</span>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="8">
                                    <center>No data found.</center>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#form-filter-btn').click(function () {
                var url = '{{ route($base_route.'.index') }}';
                var name = $('input[name="filter_name"]').val();
                var phone = $('input[name="filter_phone"]').val();
                var email = $('input[name="filter_email"]').val();
                var created_at = $('input[name="filter_created_at"]').val();
                var created_at_bs = $('input[name="filter_created_at_bs"]').val();
                var status = $('select[name="filter_status"]').val();
                var source = $('select[name="filter_bills"]').val();
                var type = $('select[name="filter_type"]').val();
                var flag = false;

                if (name) {
                    url = url + '?filter_name=' + name;
                    flag = true;
                }

                if (phone) {
                    if (flag) {
                        url = url + '&filter_phone=' + phone;
                    } else {
                        url = url + '?filter_phone=' + phone;
                        flag = true;
                    }
                }
                if (email) {
                    if (flag) {
                        url = url + '&filter_email=' + email;
                    } else {
                        url = url + '?filter_email=' + email;
                        flag = true;
                    }
                }

                if (type) {
                    if (flag) {
                        url = url + '&filter_type=' + type;
                    } else {
                        url = url + '?filter_type=' + type;
                        flag = true;
                    }
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
                if (source) {
                    if (flag) {
                        url = url + '&filter_bills=' + source;
                    } else {
                        url = url + '?filter_bills=' + source;
                        flag = true;
                    }
                }

                location.href = url;

            });

        });

        customNepaliDatePicker('filter_created_at');

        
        $('.confirm-delete-button').on('click', function (event) {
            event.preventDefault();
            var id = $(this).val();
            Swal.fire({
                title: 'Do you want to delete?',
                text: "You won't be able to revert this!",
                type: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                html: false
            }).then((result) => {
                if (result.value) {
                    var url = '{{ route($base_route.'.index') }}';
                    $.ajax({
                        url: '{{ route($base_route.'.destroy',["feedback"=> '.id.']) }}',
                        method: 'delete',
                        data: {
                            _token: '{{ csrf_token() }}',
                            key: id,
                            action: 'edit',
                        },
                        success: function (response) {
                            // console.log(response);
                            location.reload();

                        }
                    });
                }
            })
        })


    </script>
@endsection