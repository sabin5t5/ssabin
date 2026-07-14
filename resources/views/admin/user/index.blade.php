@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>App::isLocale('en') ? 'List' : 'लिस्ट' ,
    'panel'=> $panel,
    ])

    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="row">
                <div class="col-md-12">
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
                        @can('create-'.Illuminate\Support\Str::lower($panel))
                            <a type="button" href="{{ route($base_route.'.create') }}"
                               class="btn btn-success btn-sm ">
                                <i class="fi fi-plus"></i> New 
                            </a>
                        @endcan
        
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
                         
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                        <thead>
                        <tr>
                            <th class="center">
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Created at</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ html()->text('filter_name__en', isset($data['request']['filter_name__en'])?$data['request']['filter_name__en']:null)->placeholder('Enter name')->class('form-control form-control-sm') }}</td>
                            <td>{{ html()->text('filter_email', isset($data['request']['filter_email'])?$data['request']['filter_email']:null)->placeholder('Enter email')->class('form-control form-control-sm') }}</td>


                            <td></td>
                            <td></td>
                            <td> 

                                <div class="input-group mb-3">
                                    {!! html()->text('filter_created_at_bs', isset($data['request']['filter_created_at_bs'])?$data['request']['filter_created_at_bs']:null)->placeholder('Published Date')->class('form-control form-control-sm nepalidate-picker')->id('filter_created_at_bs') !!}
                                    <div class="input-group-append">
                                        <button class="btn btn-danger btn-sm" type="button" id="filter_created_at_clear"><i class="fi fi-close"></i></button>
                                    </div>
                                    {!! html()->text('filter_created_at', isset($data['request']['filter_created_at'])?$data['request']['filter_created_at']:null)->class('hidden')->id('filter_created_at') !!}
                                </div>
                            </td>
                            <td> 
                                {!! html()->select('filter_status', ['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'], isset($data['request']['filter_status'])?$data['request']['filter_status']:null )->class('form-control form-control-sm') !!}
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
                        </thead>

                        <tbody id="item_list">

                        @if ($data['rows']->count() > 0)
                            @foreach($data['rows'] as $row)

                                <tr>
                                    <td class="center">
                                        @can('update-'.Illuminate\Support\Str::lower($panel))
                                        <label>
                                            <input type="checkbox" class="checkboxes" value="{{ $row->id }}"/>
                                        </label>
                                        @endcan
                                    </td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>
                                        @foreach($row->roles()->get() as $role)

                                            <span class="badge badge-primary"> {{ Illuminate\Support\Str::ucfirst($role->name) }}</span>

                                        @endforeach
                                    </td>
                                    <td><span class="nepaliDate" englishDate="{{ $row->created_at->format('Y-m-d') }}"></span></td>
                                    <td>
                                        @if($row->status == 1)
                                            <span class="badge badge-sm badge-success"> Active </span>
                                        @else
                                            <span class="badge badge-sm badge-warning"> InActive </span>
                                        @endif                                                                              
                                    </td>
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
                                <td colspan="8">
                                    <span style="margin: 10px 0px; display: block; text-align: left; float: left; line-height: 35px;"> Showing {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+1 }}
                                        to {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+$data['rows']->count() }}
                                        of {{ $data['rows']->total() }} entries</span>
                                    <span class="pull-right">{!! $data['rows']->appends(request()->query())->links() !!}</span>
                                </td>
                            </tr>
                        @else
                            <tr>
                                
                                <td colspan="8"><p>No data found.</p></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
            </div>
        </div>
    </section>

@endsection

@section('js_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#form-filter-btn').click(function () {
                var url = '{{ route($base_route.'.index') }}';
                var title_en = $('input[name="filter_title_en"]').val();
                var title_np = $('input[name="filter_title_np"]').val();
                var published_at = $('input[name="filter_published_at"]').val();
                var published_at_bs = $('input[name="filter_published_at_bs"]').val();
                var status = $('select[name="filter_status"]').val();
                var author = $('select[name="filter_author"]').val();
                var category = $('select[name="filter_category"]').val();
                var flag = false;

                if (title_en) {
                    url = url + '?filter_title_en=' + title_en;
                    flag = true;
                }

                 if (title_np) {
                    if (flag) {
                        url = url + '&filter_title_np=' + title_np;
                    } else {
                        url = url + '?filter_title_np=' + title_np;
                        flag = true;
                    }
                }

                if (category) {
                    if (flag) {
                        url = url + '&filter_category=' + category;
                    } else {
                        url = url + '?filter_category=' + category;
                        flag = true;
                    }
                }
                if (published_at) {
                    if (flag) {
                        url = url + '&filter_published_at=' + published_at;
                    } else {
                        url = url + '?filter_published_at=' + published_at;
                        flag = true;
                    }
                }

                if (published_at_bs) {
                    if (flag) {
                        url = url + '&filter_published_at_bs=' + published_at_bs;
                    } else {
                        url = url + '?filter_published_at_bs=' + published_at_bs;
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

        customNepaliDatePicker('filter_published_at');

    </script>
@endsection