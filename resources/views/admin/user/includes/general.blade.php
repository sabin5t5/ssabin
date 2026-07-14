<fieldset>
    <legend>
        User Information
    </legend>
    <div class="row">
            <div class="form-group col-md-4 col-sm-4">
                <label for="name">Full Name: <span class="text-danger">*</span></label>
                {{ html()->text('name')->class('form-control form-control-sm name')->placeholder('Enter Full Name')->required(true)  }}
            </div>
            <div class="form-group col-md-4 col-sm-4">
                <label for="email">E-mail Address: <span class="text-danger">*</span></label>
                {!! html()->email('email')->placeholder('Enter Email Address')->class('form-control form-control-sm')->required('true') !!}
            </div>
            @if (!isset($data['row']))
            <div class="form-group col-md-4 col-sm-4">
                <label for="password">Password: <span class="text-danger">*</span></label>
                {!! html()->password('password')->class('form-control form-control-sm') !!}
                @include('admin.includes.form_validation_alert', ['field' => 'password'])
            </div>
            <div class="form-group col-md-4 col-sm-4">
                <label for="password_confirmation">Confirm Password: <span class="text-danger">*</span></label>

                {!! html()->password('password_confirmation')->class('form-control form-control-sm ') !!}
                @include('admin.includes.form_validation_alert', ['field' => 'password_confirmation'])
            </div>

            @endif

            <div class="form-group col-md-4 col-sm-4">
                {{ html()->label('Status', 'status')->class('col-md-4 control-label') }}
                <div class="radio">
                    <label class="radio">
                        <input type="radio" name="status" value="1" {{ isset($data['request']['status']) && $data['request']['status']==1 ? 'checked' : (isset($data['row']['status']) && $data['row']['status']==1 ? 'checked': 'checked') }}>

                        <i></i>Active

                    </label>
                    <label class="radio">
                        <input type="radio" name="status" value="0" {{ isset($data['request']['status']) && $data['request']['status']==0 ? 'checked' : (isset($data['row']['status']) && $data['row']['status']==0 ? 'checked':'') }}>

                        <i></i>Inactive
                    </label>
                </div>
            </div>
    </div>
</fieldset>
<hr>
<fieldset>
    <legend>
        Roles
    </legend>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12">
            <div class="checkbox">
            @if(isset($data['roles']))
                    @foreach($data['roles'] as $role)
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                @if(!isset($data['row']))
                                    {!! html()->checkbox('roles[]', false)->value($role->name)->class('form-control-input') !!}
                                @else
                                    {!! html()->checkbox('roles[]', (is_array($data['row']->roles) && in_array($role->name, $data['row']->roles)) ? true : false)->value($role->name)->class('form-control-input') !!}

                                @endif
                                <i></i> {{ Illuminate\Support\Str::ucfirst($role->name) }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</fieldset>
