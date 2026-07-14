@extends('admin.includes.layout')

@section('content')


    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=>'List',
    'panel'=> $panel,
    ])

    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <table class="table table-bodered m-1">
            <tr>
                <td colspan="7" style="padding-left:5px;">
                    <a type="button" href="{{ route($base_route.'.create') }}"
                       class="btn btn-sm btn-primary">
                        <i class="fi fi-plus"></i>
                    </a>
                </td>
            </tr>
        </table>

        <table class="table m-0">
            <thead>
            <tr>
                <th class="bb-0 text-gray-500 font-weight-normal fs--14 min-w-300">Tag Name </th>
                <th class="bb-0 text-gray-500 font-weight-normal fs--14 min-w-120">No of Posts</th>
                <th class="bb-0 text-gray-500 font-weight-normal fs--14 min-w-60">Action</th>
            </tr>
            </thead>
            <tbody id="item_list">
            @if ($data['rows']->count() > 0)
                @foreach($data['rows'] as $key=>$row)

                    <tr class="odd gradeX">
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->news()->count() }}</td>
                        <td>
                            <div class="btn-group">
                                <!-- <a type="button" href="{{ route($base_route.'.show', $row->id) }}"
                                   class="btn btn-icon-only btn-success btn-sm xow-show">
                                    <i class="fi fi-eye"></i>
                                </a> -->
                                @can('edit-'.Illuminate\Support\Str::lower($panel))
                                <a type="button" href="{{ route($base_route.'.edit', $row->id) }}"
                                   class="btn btn-icon-onlybtn btn-info btn-sm row-edit">
                                    <i class="fi fi-pencil"></i>
                                </a>
                                @endcan
                                <span>
                                @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                                    <button class="btn btn-icon-only btn-danger btn-sm confirm-delete" value = "{{$row->id}}">
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
                        <span class="pull-right">{!! $data['rows']->appends(request()->query())->links() !!}</span>
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
    </section>

@endsection

@section('js_scripts')
<script type="text/javascript">
        $(document).ready(function () {
            

        });


    </script>
@endsection