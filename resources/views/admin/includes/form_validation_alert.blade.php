@if ($errors->has($field))
    <span class="alert-danger col-sm-offset-3">
        <strong>{{ $errors->first($field) }}</strong>
    </span>
@endif