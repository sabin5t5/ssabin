<div class="card card-default">
    <div class="card-body" id="print_source_report">
        <center><h5 class="card-title bolder"> {{ App::isLocale('en')? 'Recent Activities' : 'पछिल्ला गतिविधिहरु' }} </h5></center>
        <ul class="list-group list-group-flush rounded overflow-hidden">
            @foreach($data['activitylogs'] as $activitylog)										
            <li class="list-group-item p-2">
                <div class="d-flex">

                    <div class="badge badge-{{ config('activitylog.action.'.$activitylog->action.'.color') }} badge-soft badge-ico-sm rounded-circle float-start">
                        <i class="{{ config('activitylog.action.'.$activitylog->action.'.icon') }}"></i>
                    </div>

                    <div class="pl--12 pr--12">
                        <p class="text-dark font-weight-medium m-0">
                        {{ App\Models\User::where('id', $activitylog->user_id)->first()->name }} {!! $activitylog->message !!}.
                        </p>

                        <p class="m-0">
                        ({{ $activitylog->created_at->diffForHumans()}})
                        </p>
                    </div>

                </div>
            </li>
            @endforeach

        </ul>
    </div>
    <div class="card-footer">
        <a href="#" class="btn btn-success btn-sm">View All</a>
    </div>
</div>


