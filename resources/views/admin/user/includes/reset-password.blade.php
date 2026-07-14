@if(isset($data['row']))
<div class="row">
    <div class="form-group col-md-6">
        {{ html()->label('Password', 'password')->class('control-label') }}
        {!! html()->password('password')->class('form-control form-control-sm ') !!}
    </div>
    <div class=" form-group col-md-6">
        {{ html()->label('Password Confirm', 'password_confirmation')->class('control-label') }}
        {!! html()->password('password_confirmation')->class('form-control form-control-sm ') !!}
    </div>
</div>
@endif