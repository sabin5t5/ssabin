@extends('admin.includes.layout')

@section('content')

<section class="rounded mb-3">
    @include('admin.includes.flash-notification')
    <div class="row pt-4">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <strong>Project Size:</strong>
                        <div>{{ $data['project_size'] ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <strong>Database Size:</strong>
                        <div>{{ $data['database_size'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('admin.dashboard.includes.activity_logs')
        </div>
    </div>
  	
</section>
@endsection
@section('js_scripts')
    <script src="{{ asset(config('custom.front_template').'/js/create_front_chart.js') }}"></script>
    <script type="text/javascript">

        
        
        </script>
@endsection