@extends('admin.includes.layout')

@section('content')

<section class="rounded mb-3">
    @include('admin.includes.flash-notification')

    <div class="row">
	   		<div class="col-md-2"></div>
	        <div class="col-md-8" style="text-align:center;">
	        <span style="font-size: 18px; font-weight: bold;">
            {{ App::isLocale('en')? $data['title_en'] : $data['title_np']}}
		    </span>
	            <video width="100%" height="auto" controls>
				    	<source src="{{route('admin.video_link', ['video_link' => $data['video_link']] ) }}" type="video/mp4">
				</video>
	        </div>
	   		<div class="col-md-2"></div>
	    </div>
  	
</section>
@endsection