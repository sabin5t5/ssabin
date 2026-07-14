{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }} 
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('SMS NOTICE', 'sms_notice')->class('control-label') }}
            {{ html()->select('sms_notice', [false=> 'Disable', true=> 'Enable'], isset($data['rows']['sms_notice']) ? $data['rows']['sms_notice'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('SMS Provider', 'sms_provider')->class('control-label') }}
            {{ html()->select('sms_provider', ['sparrow_sms'=> 'Sparrow SMS', 'aakash_sms'=> 'Aakash SMS', 'government'=> 'DOIT SMS',], isset($data['rows']['sms_provider']) ? $data['rows']['sms_provider'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('SMS IDENTITY', 'sms_from')->class('control-label') }}
            {{ html()->text('sms_from', isset($data['rows']['sms_from']) ? $data['rows']['sms_from'][0]->config_values : '')->placeholder('MAIL IDENTITY')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            {{ html()->label('SMS API TOKEN', 'sms_api_token')->class('control-label') }}
            {{ html()->text('sms_api_token', isset($data['rows']['sms_api_token']) ? $data['rows']['sms_api_token'][0]->config_values : '')->placeholder('SMS API TOKEN')->class('form-control form-control-sm') }}

        </div>
    </div>
</div>
@can('update-site configuration')
<div class="row">
    <div class="form-group mb-3">
            <div class="col-md-12">
                <button type="submit" name="submit"  class="btn btn-sm btn-info"> Update </button>
            </div>
    </div>
</div>
@endcan
{{ html()->form()->close() }}
<hr>
{{ html()->form('POST')->route($base_route.'.sendTestSMS')->open() }} 
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('Test Phone Number', 'test_phone')->class('control-label') }}
            {{ html()->text('test_phone', null)->placeholder('Test Receiver Phone Number')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('Test Message', 'test_msg')->class('control-label') }}
            {{ html()->text('test_msg', null)->placeholder('Test SMS Message')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-3 mt-4">
        <button type="submit" name="submit"  class="btn btn-sm btn-danger">Send </button>
    </div>
</div>
{{ html()->form()->close() }}



