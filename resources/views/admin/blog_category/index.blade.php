@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>'List',
    'panel'=> $panel,
    ])

    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')

        <div class="col-sm-12">
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <td></td>
                        <th>Blog Category Name</th>
                        <th>No of Blogs</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="item_list">
                    @if ($data['rows']->count() > 0)
                        @foreach($data['rows'] as $key=>$row)

                            <tr class="odd gradeX">
                                <td class="center">
                                    <label>
                                        <input type="checkbox" class="checkboxes" value="{{ $row->id }}"/>
                                    </label>
                                </td>
                                <td>{{ $row->category_name }}</td>
                                <td>{{ $row->blogs()->count() >= 0  ? $row->blogs()->count() : '-' }}</td>
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
                                <td>
                                    <div class="btn-group">
                                        @can('show-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                           class="btn btn-icon-only btn-success btn-sm xow-show">
                                            <i class="fi fi-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.edit', $row->id) }}"
                                           class="btn btn-icon-onlybtn btn-info btn-sm row-edit">
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
                            <td colspan="7">
                            <span style="margin: 10px 0px; display: block; text-align: left; float: left; line-height: 35px;"> Showing {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+1 }}
                                to {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+$data['rows']->count() }}
                                of {{ $data['rows']->total() }} entries</span>
                                <span class="float-right">{!! $data['rows']->appends($data['request'])->links() !!}</span>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7">
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
           //

        });


    </script>
    @include('admin.includes.jquery_sortable')
@endsection