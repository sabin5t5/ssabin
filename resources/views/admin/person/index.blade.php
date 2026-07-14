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
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Advance Filter</legend>
                    <div class="row mb-2">
                        <div class="col-md-3 col-sm-3">
                            <label>Name</label> 
                            {{ html()->text('filter_name', isset($data['request']['filter_name'])?$data['request']['filter_name']:null)->placeholder('Enter Name')->class('form-control form-control-sm') }}
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Title</label> 
                            {{ html()->text('filter_title', isset($data['request']['filter_title'])?$data['request']['filter_title']:null)->placeholder('Enter Title')->class('form-control form-control-sm') }}
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Created Date From : </label>
                            <div class="input-group mb-3">
                                {!! html()->text('filter_created_at_from', isset($data['request']['filter_created_at_from'])?$data['request']['filter_created_at_from']:null)->class('form-control form-control-sm datepicker')->id('filter_created_at_from') !!}
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Created Date To : </label>
                            <div class="input-group mb-3">
                                {!! html()->text('filter_created_at_to', isset($data['request']['filter_created_at_to'])?$data['request']['filter_created_at_to']:null)->class('form-control form-control-sm datepicker')->id('filter_created_at_to') !!}
                            </div>
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
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                        <tr>
                            <th class="bb-0 font-weight-bold fs--14 min-w-300"> Full Name</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100"> Title </th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Gender</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Address</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-100"> Created At</th>
                            <th class="bb-0 font-weight-bold fs--14 min-w-150"> Action</th>
                        </tr>
                        </thead>


                        <tbody id="item_list">
                        @if ($data['rows']->count() > 0)

                            @foreach($data['rows'] as $row)

                                <tr class="odd gradeX">
                                    <td>{{ $row->first_name }} {{ $row->middle_name }} {{ $row->last_name }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->gender }}</td>
                                    <td>{{ $row->current_address }}</td>
                                    <td> {{ $row->created_at->format('Y-m-d') }}
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
                                        </div>
                                    </td>
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
                var name = $('input[name="filter_name"]').val();
                var title = $('input[name="filter_title"]').val();
                var created_at_from = $('input[name="filter_created_at_from"]').val();
                var created_at_from_bs = $('input[name="filter_created_at_from_bs"]').val();
                var created_at_to = $('input[name="filter_created_at_to"]').val();
                var created_at_to_bs = $('input[name="filter_created_at_to_bs"]').val();
                var flag = false;

                if (name) {
                    url = url + '?filter_name=' + name;
                    flag = true;
                }

                if (title) {
                    if (flag) {
                        url = url + '&filter_title=' + title;
                    } else {
                        url = url + '?filter_title=' + title;
                        flag = true;
                    }
                }

                if (created_at_from) {
                    if (flag) {
                        url = url + '&filter_created_at_from=' + created_at_from;
                    } else {
                        url = url + '?filter_created_at_from=' + created_at_from;
                        flag = true;
                    }
                }

                if (created_at_from_bs) {
                    if (flag) {
                        url = url + '&filter_created_at_from_bs=' + created_at_from_bs;
                    } else {
                        url = url + '?filter_created_at_from_bs=' + created_at_from_bs;
                        flag = true;
                    }
                }

                if (created_at_to) {
                    if (flag) {
                        url = url + '&filter_created_at_to=' + created_at_to;
                    } else {
                        url = url + '?filter_created_at_to=' + created_at_to;
                        flag = true;
                    }
                }

                if (created_at_to_bs) {
                    if (flag) {
                        url = url + '&filter_created_at_to_bs=' + created_at_to_bs;
                    } else {
                        url = url + '?filter_created_at_to_bs=' + created_at_to_bs;
                        flag = true;
                    }
                }
                location.href = url;

            });

        });

        customNepaliDatePicker('filter_created_at_from');
        customNepaliDatePicker('filter_created_at_to');


    </script>
@endsection