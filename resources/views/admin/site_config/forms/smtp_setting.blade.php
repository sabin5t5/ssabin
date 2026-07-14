{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }} 
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('MAIL NOTICE', 'mail_notice')->class('control-label') }}
            {{ html()->select('mail_notice', [false=> 'Disable', true=> 'Enable'], isset($data['rows']['mail_notice']) ? $data['rows']['mail_notice'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('MAIL FROM NAME', 'mail_from_name')->class('control-label') }}
            (<small class="label label-success">Eg. <code>Org Name</code></small>)
            {{ html()->text('mail_from_name', isset($data['rows']['mail_from_name']) ? $data['rows']['mail_from_name'][0]->config_values : '')->placeholder('MAIL FROM NAME')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('MAIL FROM ADDRESS', 'mail_from_address')->class('control-label') }}
            (<small class="label label-success">Eg. <code>info@domainname.com</code></small>)
            {{ html()->text('mail_from_address', isset($data['rows']['mail_from_address']) ? $data['rows']['mail_from_address'][0]->config_values : '')->placeholder('MAIL FROM ADDRESS')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL MAILER', 'mail_mailer')->class('control-label') }}
            (<small class="label label-success">Eg. <code>smtp, ses, mailgun. postmark, sendmail, log, array</code></small>)
            {{ html()->text('mail_mailer', isset($data['rows']['mail_mailer']) ? $data['rows']['mail_mailer'][0]->config_values : '')->placeholder('MAIL MAILER')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL HOST', 'mail_host')->class('control-label') }}
            (<small class="label label-success">Eg. <code>smtp.mailgun.org, smtp.nepal.gov.np, smtp.gmail.com</code></small>)
            {{ html()->text('mail_host', isset($data['rows']['mail_host']) ? $data['rows']['mail_host'][0]->config_values : '')->placeholder('MAIL HOST')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL PORT', 'mail_port')->class('control-label') }}
            (<small class="label label-success">Eg. <code>587, 465, 25, 2525 </code></small>)
            {{ html()->text('mail_port', isset($data['rows']['mail_port']) ? $data['rows']['mail_port'][0]->config_values : '')->placeholder('MAIL PORT')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL USERNAME', 'mail_username')->class('control-label') }}
            {{ html()->text('mail_username', isset($data['rows']['mail_username']) ? $data['rows']['mail_username'][0]->config_values : '')->placeholder('MAIL USERNAME')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL PASSWORD', 'mail_password')->class('control-label') }}
            {{ html()->text('mail_password', isset($data['rows']['mail_password']) ? $data['rows']['mail_password'][0]->config_values : '')->placeholder('MAIL PASSWORD')->class('form-control form-control-sm') }}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('MAIL ENCRYPTION', 'mail_encryption')->class('control-label') }}
            (<small class="label label-success">Eg. <code>tls, ssl </code></small>)
            {{ html()->text('mail_encryption', isset($data['rows']['mail_encryption']) ? $data['rows']['mail_encryption'][0]->config_values : '')->placeholder('MAIL ENCRYPTION')->class('form-control form-control-sm') }}

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
{{ html()->form('POST')->route($base_route.'.sendTestMail')->open() }} 
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('Test Mail Address', 'test_mail_address')->class('control-label') }}
            (<small class="label label-success">Eg. <code>youmail@domain.com</code></small>)
            {{ html()->email('test_mail_address', null)->placeholder('Test Mail Address')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-3 mt-4">
        <button type="submit" name="submit"  class="btn btn-sm btn-danger">Send </button>
    </div>
</div>
{{ html()->form()->close() }}



