@extends('admin.includes.layout')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    pre {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
        overflow: auto;
        color:#ccc;
    }
    </style>
<section class="rounded mb-3">
    @include('admin.includes.flash-notification')
  	<div class="card card-default">
        <div class="card-body">
            <div class="card-title"><h1>Error Logs</h1></div>
            <form action="{{ route('admin.logs_clear') }}" method="POST">
                @csrf
                <button type="submit" class="button">Clear Log</button>
            </form>
            <pre>{{ $logContent }}</pre>
        </div>
    </div>
</section>
@endsection