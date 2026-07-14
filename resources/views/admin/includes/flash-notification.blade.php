<div class="col-md-12">
@if (session()->has('success_message'))
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span class="fi fi-close" aria-hidden="true"></span>
        </button>
        <p>{!! session()->get('success_message') !!}</p>
    </div>
@endif

@if (session()->has('error_message'))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span class="fi fi-close" aria-hidden="true"></span>
        </button>
        <p>{!! session()->get('error_message') !!}</p>
    </div>
@endif

@if (count($errors))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span class="fi fi-close" aria-hidden="true"></span>
        </button>
        @foreach ($errors->all() as $message)
            <p>.{!! $message !!}</p>
            @endforeach
    </div>
@endif
</div>