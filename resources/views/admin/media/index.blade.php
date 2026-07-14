@extends('admin.includes.layout')

@section('content')
<style>
.image-container {
  position: relative; overflow: hidden; padding:10px; float: left;
}

/*.image {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}*/

.image{
    display:inline-block;
    width:200px;
    height:220px;
    overflow:hidden;
    vertical-align:top;
}

/*.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}*/

.image-container:hover .image {
  opacity: 0.7;
}

/*.image-container:hover .middle {
  opacity: 1;
}*/

/*.link_btn {
  color: white;
  padding: 16px 32px;
}*/
</style>

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
                                    <i class="fi fi-go-back"></i>Active Media
                                </a>
                            </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead>
                    @if($data['is_trash'] == false)
                        <tr>
                            <td colspan="8">
                               
                                <div class="col-md-6 float-left">
                                {!! html()->text('filter_caption_title', isset($data['request']['filter_caption_title'])?$data['request']['filter_caption_title']:null)->placeholder('Image Name')->class('form-control form-control-sm') !!}
                                </div>
                                    <div class="col-md-4 float-right">
                                        <button type="submit" class="btn btn-warning btn-sm" id="form-filter-btn">
                                            <i class="fi fi-search"></i>
                                        </button>
                                        <a href="{{ route($base_route.'.index') }}" class="btn btn-dark btn-sm">
                                            <i class="fi fi-reload"></i>
                                        </a>
                                    </div>
                            </td>
                        </tr>
                    @endif
                    </thead>
                    <tbody>
                        <tr colspan="8">
                            <td>
                                @if ($data['rows']->count() > 0)
                                    @foreach($data['rows'] as $row)
                                    
                                        <div class="image-container">
                                            <input type="text" value="{{ asset('images/media/'.$row->image) }}" id="myInput" class="hidden">
                                            @can('update-'.Illuminate\Support\Str::lower($panel))
                                            <a  href="{{ route($base_route.'.edit', $row->id) }}">

                                                <img src="{{ asset('images/media/'.$row->image) }}" class="image">

                                            </a>
                                            @else
                                                <img src="{{ asset('images/media/'.$row->image) }}" class="image">
                                            @endcan
                                        </div>
                                       
                                    @endforeach
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8">
                                <span style="margin: 10px 0px; display: block; text-align: left; float: left; line-height: 35px;"> Showing {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+1 }}
                                    to {{ $data['rows']->perPage() * ($data['rows']->currentPage()-1)+$data['rows']->count() }}
                                    of {{ $data['rows']->total() }} entries</span>
                                <span class="float-right">{!! $data['rows']->appends(request()->query())->links() !!}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- END SAMPLE TABLE PORTLET-->
        </div><!-- /span -->
    </section>

@endsection

@section('js_scripts')
    <script type="text/javascript">
        function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        // copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("Copied the text: " + copyText.value);
    }
        $(document).ready(function () {
            $('#form-filter-btn').click(function () {
                var url = '{{ route($base_route.'.index') }}';
                var name = $('input[name="filter_caption_title"]').val();
                var status = $('select[name="filter_status"]').val();
                var flag = false;

                if (name) {
                    url = url + '?filter_caption_title=' + name;
                    flag = true;
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
            $('.confirm-delete-button').on('click', function (event) {
                event.preventDefault();
                var id = $(this).val();
                let delete_route = "{{ route($base_route.'.restore', ':id')  }}";
                delete_route = delete_route.replace(':id', id);
                swal({
                    title: 'Do you want to delete?',
                    text: "You won't be able to revert this!",
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    html: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        var url = '{{ route($base_route.'.index') }}';
                        $.ajax({
                            url: delete_route,
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
                });
            });

            $('.confirm-restore').on('click', function (event) {
                event.preventDefault();
                var id = $(this).val();
                let restore_url = "{{ route($base_route.'.restore', ':id')  }}";
                restore_url = restore_url.replace(':id', id);
                swal({
                    title: 'Do you want to restore?',
                    text: "You won't be able to revert this!",
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    html: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        var url = '{{ route($base_route.'.index') }}';
                        $.ajax({
                            url: restore_url,
                            method: 'post',
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
            });
            $('.confirm-forcedelete').on('click', function (event) {
                event.preventDefault();
                var id = $(this).val();
                let forcedelete_url = "{{ route($base_route.'.forcedelete', ':id')  }}";
                forcedelete_url = forcedelete_url.replace(':id', id);
                swal({
                    title: 'Do you want to force delete?',
                    text: "You won't be able to revert this!",
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    html: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        var url = '{{ route($base_route.'.index') }}';
                        $.ajax({
                            url: forcedelete_url,
                            method: 'post',
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
            });

        });

    </script>

    @include('admin.includes.jquery_sortable')
    
@endsection