@extends('admin.includes.layout')

@section('content')

<div class="page-title bg-transparent b-0">

    <h1 class="h4  mb-0 px-3 font-weight-normal">
        Activity Logs
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-sublime fs--13 pb-2 px-3">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Activity Logs</li>
        </ol>
    </nav>

</div>
<section class="rounded mb-3">
  	<div class="card card-default">
        <div class="card-body">
            <ul class="list-group list-group-flush rounded slimscroll" data-slimscroll-visible="true">
                @foreach($data['activitylogs'] as $activitylog)
                <li class="list-group-item pt-1 pb-1">
                    <div class="d-flex">

                        <div class="badge badge-{{ config('activitylog.action.'.$activitylog->action.'.color') }} badge-soft badge-ico-sm rounded-circle float-start">
                            <i class="fi {{ config('activitylog.action.'.$activitylog->action.'.icon') }}"></i>
                        </div>

                        <div class="pl--12 pr--12">
                            <p class="text-dark font-weight-medium m-0">
                                {{ App\Models\User::where('id', $activitylog->user_id)->first()->name }} {!! $activitylog->message !!}.
                            </p>

                            <p class="m-0">
                                {{ $activitylog->created_at->diffForHumans()}}
                            </p>
                        </div>

                    </div>
                </li>
                @endforeach
            </ul>
            <!-- panel footer -->
            <span style="margin: 10px 0px; display: block; text-align: left; float: left; line-height: 35px;"> Showing {{ $data['activitylogs']->perPage() * ($data['activitylogs']->currentPage()-1)+1 }}
                            to {{ $data['activitylogs']->perPage() * ($data['activitylogs']->currentPage()-1)+$data['activitylogs']->count() }}
                            of {{ $data['activitylogs']->total() }} entries</span>
            <span class="float-right">{!! $data['activitylogs']->links() !!}</span>
            </div>
        </div>
    </div>
</section>
@endsection