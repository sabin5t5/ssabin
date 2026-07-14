@extends('layouts.email_notification')

@section('content')
    <div class="header"></div>
    <div class="body feedback">
        <h4>{{ $data['subject'] }}</h4>
        <p>{!! $data['message'] !!}</p>    
        <p>Thank you</p>
        <br>
        <p>{{ config('app.name') }}</p>
    </div>
@endsection