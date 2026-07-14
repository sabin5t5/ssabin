@extends('layouts.email_notification')

@section('content')
    <div class="header">New Feedback</div>
    <div class="body feedback">
        <strong>Full Name:</strong> {{ $data['name'] }} <br>
        <strong>Email:</strong> {{ $data['email'] }} <br>
        <strong>Phone:</strong> {{ $data['phone'] }} <br>
        <strong>Address:</strong> {{ $data['address'] }} <br>
        @if($data['bills_id'])
        <strong>Bills:</strong> 
            @php $bill = App\Models\Admin\Bills::where('id', $data['bills_id'])->first();@endphp
            {{ isset($bill)&& $bill? $bill->title_en : '' }} <br>
        @endif
        <strong>Type:</strong> {{config('custom.feedbackTypes.'.$data['type'].'.name_np')}} <br>
        <strong>Feedback/Advise: </strong><p>{!! $data['description'] !!}</p>  
        <strong>IP Address:</strong>{{ $data['ip_address'] }}<br>
        <strong>User Agent:</strong> {{ $data['user_agent'] }}       
    </div>
@endsection