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
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead>
                    <tr>
                        <td colspan="7" style="padding-left:5px;">
                            @if(count($data['backups']) > $data['per_page'])
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

                            {{ html()->form('POST')->route($base_route.'.store')->open() }}
                                <button type="submit" name="submit" class="btn btn-sm btn-success"><i class="fi fi-plus"></i> New Backup</button>
                            {{ html()->form()->close() }}
                            @endcan
                        </td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Time</th>
                        <th>Filesize</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody id="table-tbody">

                    @if (count($data['backups']) > 0)
                        @foreach($data['backups'] as $key=>$row)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimeStamp($row['last_modified'])->diffForHumans() }}</td>
                                <td>{{ round((int)$row['file_size']/1048576, 2).' MB' }}</td>
                                <td>
                                    <div class="btn-group">
                                        @can('download-'.Illuminate\Support\Str::lower($panel))
                                        <a type="button" href="{{ route($base_route.'.download',['disk'=> $row['disk'], 'path'=> urlencode($row['file_path']) ,  'file_name'=>$row['file_name']])}}"
                                           class="btn btn-icon-only btn-success btn-sm row-show">
                                            <i class="fi fi-cloud-download"></i> Download
                                        </a>
                                        @endcan
                                        <span>
                                        @can('delete-'.Illuminate\Support\Str::lower($panel))
                                            <button class="btn btn-icon-only btn-danger btn-sm confirm-delete"
                                                    attr="{{ route($base_route.'.destroy',['file_name'=>$row['file_name']]) }}">
                                                <i class="fi fi-thrash"></i> Delete
                                            </button>
                                            {{ html()->form('delete')->route($base_route.'.destroy',['file_name'=>$row['file_name']])->open() }}
                                            {{ html()->form()->close() }}
                                            @endcan
                                        </span>
                                    </div>



                                </td>
                            </tr>

                        @endforeach
                        <tr>
                            <td colspan="8"></td>
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
        });

    </script>
@endsection