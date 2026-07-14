@extends('layouts.email_notification')

@section('content')
    <div class="header">Feedback received</div>
    <div class="body feedback">
        <p>Hi {{ $data['name'] }},</p>
        <p>This is an auto-generated email confirmation to acknowledge that your feedback has been received on our site <a href="{{ route('main') }}">{{ config('app.name') }}</a>.</p>
        <p>Thank you for messaging. We'll get back to you shortly.</p>
    </div>
@endsection