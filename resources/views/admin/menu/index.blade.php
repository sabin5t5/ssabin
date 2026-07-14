@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>App::isLocale('en') ? 'List' : 'लिस्ट' ,
    'panel'=> $panel,
    ])
    <style>
        .list > div {
            min-height: 50px;
            border-style: solid;
            border-width: 2px;
            /* text-align: center; */
            line-height: 50px;
            font-size: 20px;
            font-family: Helvetica;
        }


        .half {
            display: inline-block;
            width: 49%;
            padding: 0;
            margin: 0;
            vertical-align: top;
        }
        .n1 > div {
            /* background-color: lightblue; */
        }

        .n2 > div {
            /* background-color: lightgreen */
        }

        .list {
            padding: 30px;
        }

    </style>


    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="row">
                <div class="col-md-8">
                    @can('create-'.Illuminate\Support\Str::lower($panel))
                        <a type="button" href="{{ route($base_route.'.create') }}"
                           class="btn btn-success btn-sm ">
                            <i class="fi fi-plus"></i> New 
                        </a>
                    @endcan
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="portlet-body" style="display: block;">
                        @can('update-'.Illuminate\Support\Str::lower($panel))
                        {{ html()->form('POST')->route($base_route.'.update-rank')->id('sorting-update-form')->class('form-horizontal')->open() }}
                        @endcan
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Menu Type</th>
                                <th>Name English</th>
                                <th>Name Nepali</th>
                                <th>URL</th>
                                <th>Parent Menu</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @can('update-'.Illuminate\Support\Str::lower($panel))
                                <tbody id="table-tbody">
                            @else
                                <tbody id="item_list">
                            @endcan
                            @if ($data['rows']->count() > 0)
                                @foreach($data['rows'] as $key=>$row)
                                <tr class="odd gradeX">
                                    <td>{{ config('custom.menu_types.'.$row->menu_type) }}</td>
                                    <td>{{ $row->name_en }}</td>
                                    <td>{{ $row->name_np }}</td>
                                    <td>{{ $row->value }}</td>
                                    @php
                                        $parent_name = '-';
                                       if($row->parent_id)
                                       {
                                           $parent = App\Models\Admin\Menu::where('id', $row->parent_id)->first();
                                           if($parent)
                                            $parent_name = $parent->name_np;
                                       }   

                                    @endphp
                                    <td>{{ $parent_name }}</td>
                                    <input type="hidden" id="hidden_id" name="hidden_id[]" value="{{ $row->id }}">
                                        <td>
                                            @if($row->status == 1)
                                                <span class="badge badge-sm badge-success"> Active </span>
                                            @else
                                                <span class="badge badge-sm badge-warning"> InActive </span>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @can('update-'.Illuminate\Support\Str::lower($panel))
                                                <a type="button" href="{{ route($base_route.'.edit', $row->id) }}" class="btn btn-icon-only btn-info btn-sm row-edit">
                                                    <i class="fi fi-pencil"></i>
                                                </a>
                                                @endcan
                                                @can('delete-'.Illuminate\Support\Str::lower($panel))
                                                <button class="btn btn-icon-only btn-danger
                                                    btn-sm confirm-delete-button" value = "{{ $row->id }}">
                                                    <i class="fi fi-thrash"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <center>No data found.</center>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @can('update-'.Illuminate\Support\Str::lower($panel))
                        {{ html()->form()->close() }}
                        @endcan
                    </div>
                </div>
                <div class="col-md-3">
                    <div id="list1" class="list">
                        @foreach($data['menus'] as $menu)
                            @if(isset($menu['main']))
                            @if(count($menu) == 1)
                                <div> {{ $menu['main']->name_np }} </div>
                            @else
                                <div> {{ $menu['main']->name_np }}
                                    <div class="list n1">
                                        @foreach($menu['data'] as $m)
                                            <div>{{ $m->name_np }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div>
    </section>
    @endsection
    @section('js_scripts')
        <script type="text/javascript">
        $(document).ready(function () {
           $('.confirm-delete-button').on('click', function (event) {
                event.preventDefault();
                var id = $(this).val();
                console.log(id);
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
                            url: '{{ route($base_route.'.destroy',["menu"=>'.id.']) }}',
                            method: 'delete',
                            data: {
                                _token: '{{ csrf_token() }}',
                                key: id,
                                action: 'edit',
                            },
                            success: function (response) {
                                location.reload();
                            }
                        });
                    }
                })
            })
        });
        </script>
    @include('admin.includes.jquery_sortable')

    @endsection