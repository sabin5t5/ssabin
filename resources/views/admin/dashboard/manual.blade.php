@extends('admin.includes.layout')

@section('content')

<section class="rounded mb-3">
    @include('admin.includes.flash-notification')

    <div class="row">
	   		<div class="col-md-1"></div>
	        <div class="col-md-10" style="text-align:center;">
	        <span style="font-size: 18px; font-weight: bold;">
            {{ App::isLocale('en')? 'User Manual' : 'प्रयोगकर्ता पुस्तिका'}}
		    </span>
                    <iframe src="{{route('admin.user_manual_file', ['manual_link' => $data['link']] ) }}" style="width:100%; height:80vh;"></iframe>
	        </div>
	   		<div class="col-md-1"></div>
	    </div>
  	
</section>
@endsection