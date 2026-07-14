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
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Advance Filter</legend>
                    <div class="row mb-2">
                        <div class="col-md-3 col-sm-3">
                            <label>Title</label> 
                            {{ html()->text('filter_title', isset($data['request']['filter_title'])?$data['request']['filter_title']:null)->placeholder('Enter Title')->class('form-control form-control-sm') }}
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Blog Category </label>
                            {!! html()->select('filter_category', $data['blog_categories'], isset($data['request']['filter_category'])?$data['request']['filter_category']:null )->class('form-control form-control-sm') !!}
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Published Date From : </label>
                            <div class="input-group mb-3">
                                {!! html()->text('filter_published_from', isset($data['request']['filter_published_from'])?$data['request']['filter_published_from']:null)->class('form-control form-control-sm datepicker')->id('filter_published_from') !!}
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Published Date To : </label>
                            <div class="input-group mb-3">
                                {!! html()->text('filter_published_to', isset($data['request']['filter_published_to'])?$data['request']['filter_published_to']:null)->class('form-control form-control-sm datepicker')->id('filter_published_to') !!}
                            </div>
                        </div>
                        
                        <div class="col-md-1 col-sm-1">
                            <label>Status : </label>
                            {!! html()->select('filter_status', ['' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'], isset($data['request']['filter_status'])?$data['request']['filter_status']:null )->class('form-control form-control-sm') !!}
                            
                        </div>
                       
                        <div class="col-md-2 col-sm-2">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-warning btn-sm" id="form-filter-btn">
                                    <i class="fi fi-search"></i> Filter
                                </button>
                                <a href="{{ route($base_route.'.index') }}" class="btn btn-dark btn-sm">
                                    <i class="fi fi-reload"></i> Reset
                                </a>
                            </div>
                        </div>
                </fieldset>
                @endif

                @if($data['is_trash'] == false && $data['is_archive'] == false)
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
                    @canany(['restore-'.Illuminate\Support\Str::lower($panel), 'forceDelete-'.Illuminate\Support\Str::lower($panel)])
                    <a type="button"
                       href="{{ route($base_route.'.index', ['data-show'=>'trashed']) }}"
                       class="btn btn-danger btn-sm float-right" data-toggle="tooltip" title="Deleted Records">
                        <i class="fa fa-trash"></i>Trash List
                    </a>
                    @endcanany
                    @canany(['show-'.Illuminate\Support\Str::lower($panel)])
                    <a type="button"
                       href="{{ route($base_route.'.index', ['data-show'=>'archived']) }}"
                       class="btn btn-warning btn-sm float-right" data-toggle="tooltip" title="Archived Records">
                        <i class="fa fa-trash"></i>Archived List
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
                @elseif($data['is_trash'] == false && $data['is_archive'] == true)
                    @can('show-'.Illuminate\Support\Str::lower($panel))
                        <div class="btn-group float-right">
                            <a type="button" href="{{ route($base_route.'.index') }}"
                            class="btn btn-success btn-sm " data-toggle="tooltip" title="Active Records">
                                <i class="fa fa-refresh"></i>Active Blog Lists
                            </a>
                        </div>
                    @endcan
                @else
                    @can('show-'.Illuminate\Support\Str::lower($panel))
                    <div class="btn-group float-right">
                        <a type="button" href="{{ route($base_route.'.index') }}"
                           class="btn btn-success btn-sm " data-toggle="tooltip" title="Active Records">
                            <i class="fa fa-refresh"></i>Active Blog Lists
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
                            <th class="bb-0 font-weight-bold fs--14 min-w-300"> Title</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100"> Blog Category </th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Status</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100"> Published At</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Action</th>
                        </tr>
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
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->category }} 
                                    </td>
                                    <td>
                                        @if($row->status == 1)
                                            <span class="badge badge-sm badge-success"> Active </span>
                                        @else
                                            <span class="badge badge-sm badge-warning"> InActive </span>
                                        @endif
                                        @if($row->is_highlight == 1)
                                            <span class="badge badge-sm badge-info"> Highlight </span>
                                        @endif
                                        @if($data['is_archive'])
                                            <span class="badge badge-sm badge-warning"> Archived At: {{ $row->archive_at->format('Y-m-d') }} </span>
                                        @endif
                                        @if($row->deleted_at != null)
                                            <span class="badge badge-sm badge-danger"> Deleted </span>
                                        @endif
                                    </td>
                                    <td> {{ $row->published_at->format('Y-m-d') }}
                                        @if($row->archive_at)
                                        <span class="badge badge-warning">
                                            Archived on:{{ $row->archive_at->format('Y-m-d') }}
                                        </span>
                                        @endif
                                    
                                    </td>
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
                                    @endif
                                </tr>

                            @endforeach
                            <tr>
                                <td colspan="8">
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
                var title = $('input[name="filter_title"]').val();
                var published_from = $('input[name="filter_published_from"]').val();
                var published_from_bs = $('input[name="filter_published_from_bs"]').val();
                var published_to = $('input[name="filter_published_to"]').val();
                var published_to_bs = $('input[name="filter_published_to_bs"]').val();
                var status = $('select[name="filter_status"]').val();
                var category = $('select[name="filter_category"]').val();
                var flag = false;

                if (title) {
                    url = url + '?filter_title=' + title;
                    flag = true;
                }
                if (category) {
                    if (flag) {
                        url = url + '&filter_category=' + category;
                    } else {
                        url = url + '?filter_category=' + category;
                        flag = true;
                    }
                }
                if (published_from) {
                    if (flag) {
                        url = url + '&filter_published_from=' + published_from;
                    } else {
                        url = url + '?filter_published_from=' + published_from;
                        flag = true;
                    }
                }

                if (published_from_bs) {
                    if (flag) {
                        url = url + '&filter_published_from_bs=' + published_from_bs;
                    } else {
                        url = url + '?filter_published_from_bs=' + published_from_bs;
                        flag = true;
                    }
                }

                if (published_to) {
                    if (flag) {
                        url = url + '&filter_published_to=' + published_to;
                    } else {
                        url = url + '?filter_published_to=' + published_to;
                        flag = true;
                    }
                }

                if (published_to_bs) {
                    if (flag) {
                        url = url + '&filter_published_to_bs=' + published_to_bs;
                    } else {
                        url = url + '?filter_published_to_bs=' + published_to_bs;
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

        customNepaliDatePicker('filter_published_from');
        customNepaliDatePicker('filter_published_to');


    </script>
@endsection