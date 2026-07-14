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
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="row">
                <div class="col-md-12">
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
                                    <i class="fi fi-thrash"></i>Trash List
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
                                    <i class="fi fi-reload"></i>Active Lists
                                </a>
                            </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead>
                    <tr>
                        @if($data['is_trash'] == false)
                        <th></th>
                        @endif
                        <th> Title English</th>
                        <th> Title Nepali</th>
                        <th> Published From</th>
                        <th> Published To</th>
                        <th> Status</th>
                        <th> Action</th>
                    </tr>
                    @if($data['is_trash'] == false)
                    <tr>
                        <td></td>
                        <td>{{ html()->text('filter_title_en', isset($data['request']['filter_title_en'])?$data['request']['filter_title_en']:null)->placeholder('Enter Title English')->class('form-control form-control-sm') }}</td>
                        <td>{{ html()->text('filter_title_np', isset($data['request']['filter_title_np'])?$data['request']['filter_title_np']:null)->placeholder('Enter Title Nepali')->class('form-control form-control-sm') }}</td>
                        <td>
                            <div class="input-group mb-3">
                                
                                {!! html()->text('filter_published_from_bs', isset($data['request']['filter_published_from_bs'])?$data['request']['filter_published_from_bs']:null)->placeholder('Published Date')->class('form-control form-control-sm nepalidate-picker')->id('filter_published_from_bs') !!}
                                    <div class="input-group-append">
                                        <button class="btn btn-danger btn-sm" type="button" id="filter_published_from_clear"><i class="fi fi-close"></i></button>
                                    </div>
                                {!! html()->text('filter_published_from', isset($data['request']['filter_published_from'])?$data['request']['filter_published_from']:null)->class('hidden')->id('filter_published_from') !!}
                            </div>
                        </td>

                        <td>
                            <div class="input-group">
                                {!! html()->text('filter_published_to_bs', isset($data['request']['filter_published_to_bs'])?$data['request']['filter_published_to_bs']:null)->placeholder('Published Date')->class('form-control form-control-sm nepalidate-picker')->id('filter_published_to_bs') !!}
                                    <div class="input-group-append">
                                        <button class="btn btn-danger btn-sm" type="button" id="filter_published_to_clear"><i class="fi fi-close"></i></button>
                                    </div>
                                {!! html()->text('filter_published_to', isset($data['request']['filter_published_to'])?$data['request']['filter_published_to']:null)->class('hidden')->id('filter_published_to') !!}
                            </div>
                        </td>

                        <td>{!! html()->select('filter_status', ['' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'], isset($data['request']['filter_status'])?$data['request']['filter_status']:null )->class('form-control form-control-sm') !!}</td>
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
                                    <label>
                                        <input type="checkbox" class="checkboxes" value="{{ $row->id }}"/>
                                    </label>
                                </td>
                                @endif
                                <td>{{ $row->title_en }}</td>
                                <td>{{ $row->title_np }}</td>
                                <td> <span class="nepaliDate" englishDate="{{ $row->published_from->format('Y-m-d') }}"></span></td>
                                <td> <span class="nepaliDate" englishDate="{{ $row->published_to->format('Y-m-d') }}"></span></td>
                                <td>
                                    @if($row->status == 1)
                                        <span class="badge badge-sm badge-success"> Active </span>
                                    @else
                                        <span class="badge badge-sm badge-warning"> InActive </span>
                                    @endif
                                    @if($row->deleted_at != null)
                                        <span class="badge badge-sm badge-danger"> Deleted </span>
                                    @endif
                                </td>
                                @if($data['is_trash'] ==true)
                                <td>
                                    <div class="btn-group">
                                        @can('show-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                           class="btn btn-icon-only btn-success btn-sm row-show">
                                            <i class="fi fi-eye"></i>Show
                                        </a>
                                        @endcan
                                        @can('restore-'.Illuminate\Support\Str::lower($panel))
                                        <span>
                                        <button class="btn btn-sm btn-warning  confirm-restore"
                                                attr="#" title="Restore">
                                            <i class="fi fi-go-back"></i>Restore
                                        </button>
                                        {{ html()->form('POST')->route($base_route.'.restore',$row->id)->open() }}
                                        {{ html()->form()->close() }}
                                        </span>
                                        @endcan
                                        @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                                        <span>
                                        <button class="btn btn-sm btn-danger  confirm-force-delete"
                                                attr="{{ route($base_route.'.forcedelete', $row->id) }}"
                                                 title="Delete Permanently">
                                            <i class="fi fi-thrash"></i>Delete 
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
            <!-- END SAMPLE TABLE PORTLET-->
        </div><!-- /span -->
    </section>

@endsection

@section('js_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#form-filter-btn').click(function () {
                var url = '{{ route($base_route.'.index') }}';
                var title_en = $('input[name="filter_title_en"]').val();
                var title_np = $('input[name="filter_title_np"]').val();
                var published_from = $('input[name="filter_published_from"]').val();
                var published_from_bs = $('input[name="filter_published_from_bs"]').val();
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