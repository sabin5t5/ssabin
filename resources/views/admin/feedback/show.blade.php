@extends('admin.includes.layout')

@section('content')

        @include('admin.includes.breadcrumb', [
           'base_route' => $base_route,
           'page' => 'View'
       ])
    <style>
        .slimScrollDiv {height: auto !important;}
    </style>
    <section class="rounded mb-3">
        <div class="row">
            <div class="col-md-8">
                <!-- PAGE CONTENT BEGINS -->
                <a href="{{ url('/admin/feedback') }}" title="Back" type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

                @if($data['row']->deleted_at != null)
                    @can('restore-'.Illuminate\Support\Str::lower($panel))
                    <span>
                        <button class="btn btn-sm btn-success confirm-restore"
                                attr="#">
                            <i class="fa fa-refresh"></i>Restore
                        </button>
                        {{ html()->form('POST')->route($base_route.'.restore',$data['row']->id)->open() }}
                        {{ html()->form()->close() }}
                    </span>

                    @endcan
                    @can('forceDelete-'.Illuminate\Support\Str::lower($panel))
                    <span>
                        <button class="btn btn-sm btn-danger  confirm-force-delete"
                                attr="{{ route($base_route.'.forcedelete', $data['row']->id) }}">
                            <i class="fa fa-trash"></i>Delete Permanently
                        </button>
                        {{ html()->form('POST')->route($base_route.'.forcedelete',$data['row']->id)->open() }}
                        {{ html()->form()->close() }}
                    </span>
                    @endcan
                @else
                @can('delete-'.Illuminate\Support\Str::lower($panel))
                    <span>
                        @can('delete-'.Illuminate\Support\Str::lower($panel))
                        <button class="btn btn-icon-only btn-danger btn-sm confirm-delete-button" value = "{{$data['row']->id}}">
                            <i class="fi fi-thrash"></i> Delete
                        </button>
                        @endcan
                    </span>
                @endcan
                @endif      
                <hr>
                <div class="card card-default"> 
                    <div class="card-body">
                        <table class="table table-stripped table-bordered">
                            <tr>
                                <td>{{ config('custom.feedbackTypes.'.$data['row']->type.'.name_np') }} पठाएको मिति</td>
                                <td>{{ $data['row']->created_at->format('Y-m-d') }} ( <span class="nepaliDate" englishDate="{{ $data['row']->created_at->format('Y-m-d') }}"> </span>) </td>
                            </tr>
                            <tr>
                                <td>प्रकार</td>
                                <td> {{ config('custom.feedbackTypes.'.$data['row']->type.'.name_np') }}</td>
                            </tr>
                            <tr>
                                <td>पुरा नाम</td>
                                <td><span style="color: green; font-weight: bold;">{{ $data['row']->name}}</span></td>
                            </tr>
                            <tr>
                                <td>फोन नं</td>
                                <td>{{ $data['row']->phone }}</td>
                            </tr>
                            <tr>
                                <td>ईमेल </td>
                                <td>{{ $data['row']->email }}</td>
                            </tr>
                            <tr>
                                <td> IP Address</td>
                                <td>{!! $data['row']->ip_address !!}</td>
                            </tr>
                            <tr>
                                <td> Device ID / User Agent</td>
                                <td>{!! $data['row']->user_agent !!}<</td>
                            </tr>
                            <tr>
                                <td><strong>{{config('custom.feedbackTypes.'.$data['row']->type.'.name_np')}}को संछिप्त विवरण</strong></td>
                                <td>{!! $data['row']->description !!}</td>
                            </tr>
                            <tr class="bg-info">
                                <td colspan="2"><h5>Replys</h5></td>
                            </tr>
                            @foreach($data['reply'] as $reply)
                            <tr>
                                <td>{{ $reply->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <h6>{{ $reply->subject }}  <small>( By: @php $user = App\Models\User::where('id', $reply->user_id)->first() @endphp
                                    {{ $user ? $user->name : '-'}} )</small> </h6>
                                    <p>{{ $reply->message}}</p>
                                </td>
                            </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @include('admin.includes.flash-notification')
                @can('reply-'.Illuminate\Support\Str::lower($panel))
                <div class="card card-success">
                    
                    <div class="card-body">
                        <div class="card-title"> <h4>Reply</h4> </div>
                        <form class="form-horizontal" action="{{ route('admin.feedbackreply.submit')}}" method="POST">     
                        @csrf
                        <input type="hidden" name="feedback_id" value="{{ $data['row']->id }}">   
                        <input type="hidden" name="name" value="{{ $data['row']->name }}">   
                            <div class="form-group">
                                <label class="text-center">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $data['row']->email }}">
                            </div>
                            <div class="form-group">
                                <label class=" text-center">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Subject">
                            </div>
                            <div class="form-group">
                                <label class="text-center">Message</label>
                                <textarea id="msg" name="message" class="form-control" placeholder="Message" rows="3" required=""></textarea>
                            </div>
                
                            <div class="form-group">
                                <strong class="text-center"></strong>
                                <button type="submit" class="btn btn-info btn-sm button formSubmitBtn">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
                <hr>
                <h5>Recent Activities</h5>
                @if(isset($data['activitylogs']))
                    @include('admin.includes.activity_lists')
                @endif
            </div>
                    
        </div><!-- /.page-content -->
    </section><!-- /.main-content -->

@endsection
@section('js_scripts')
<script type="text/javascript">
    $(document).ready(function () {
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

    });

</script>
@endsection