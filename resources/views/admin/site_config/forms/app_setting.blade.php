{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }} 
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">    
<div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            {{ html()->label('Current F Y', 'fiscal_year')->class('control-label') }}
            {{ html()->select('fiscal_year', [''=> 'Select']+config('custom.fiscal_year'), isset($data['rows']['fiscal_year']) ? $data['rows']['fiscal_year'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>            
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            {{ html()->label('Default Locale', 'default_locale')->class('control-label') }}
            {{ html()->select('default_locale', [''=> 'Select', 'en' =>'English', 'np'=>'Nepali'], isset($data['rows']['default_locale']) ? $data['rows']['default_locale'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>            
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            {{ html()->label('Timezone', 'timezone')->class('control-label') }}
            <small class="required">For Nepal: <code>Asia/Kathmandu</code></small>
            {{ html()->text('timezone', isset($data['rows']['timezone']) ? $data['rows']['timezone'][0]->config_values : '')->placeholder('Enter Timezone')->class('form-control form-control-sm') }}
        </div>            
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            @php
                $upload_max_filesize_kilobytes=ViewHelper::parse_size(ini_get('upload_max_filesize'));
                $upload_max_filesize_bytes= round($upload_max_filesize_kilobytes / 1024, 2) ;
            @endphp
            {{ html()->label('Allowed File Size', 'allowed_file_size')->class('control-label') }}

            <small class="required"> Max Upload Size: <code>{{$upload_max_filesize_bytes.'MB'}}</code></small>
            {{ html()->text('allowed_file_size', isset($data['rows']['allowed_file_size']) ? $data['rows']['allowed_file_size'][0]->config_values : '')->placeholder('Enter Allowed File Size')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            {{ html()->label('Allowed File Extension', 'allowed_file_extension')->class('control-label') }}
            <select name="allowed_file_extension[]" multiple='multiple' class="form-control form-control-sm select2tags" id=allowed_file_extension >
            @php $options = (isset($data['rows']['allowed_file_extension']) && isset($data['rows']['allowed_file_extension'][0]->config_values))?json_decode($data['rows']['allowed_file_extension'][0]->config_values):null; 
            @endphp
            @if($options)
                @foreach ($options as $option) 
                    <option value="{{$option}}" selected> {{$option}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('RECAPTCHA ENABLE', 'recaptcha_enable')->class('control-label') }}
            {{ html()->select('recaptcha_enable', [false=> 'Disable', true=> 'Enable'], isset($data['rows']['recaptcha_enable']) ? $data['rows']['recaptcha_enable'][0]->config_values : '')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('RECAPTCHAV3 SITEKEY', 'recaptchav3_sitekey')->class('control-label') }}
            {{ html()->text('recaptchav3_sitekey', isset($data['rows']['recaptchav3_sitekey']) ? $data['rows']['recaptchav3_sitekey'][0]->config_values : '')->placeholder('ENTER RECAPTCHAV3 SITEKEY')->class('form-control form-control-sm') }}
        </div>           
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            {{ html()->label('RECAPTCHAV3 SECRET', 'recaptchav3_secret')->class('control-label') }}
            {{ html()->text('recaptchav3_secret', isset($data['rows']['recaptchav3_secret']) ? $data['rows']['recaptchav3_secret'][0]->config_values : '')->placeholder('ENTER RECAPTCHAV3 SECRET')->class('form-control form-control-sm') }}
        </div>           
    </div>
</div>
<hr>
@can('update-site configuration')
<div class="row">
    <div class="form-group mb-3">
            <div class="col-md-12">
                <button type="submit" name="submit"  class="btn btn-info btn-sm">Update </button>
            </div>
    </div>
</div>
@endcan
{{ html()->form()->close() }}
@section('js_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2tags").select2({
                tags: true,
                tokenSeparators: [',']
            })
        });

</script>
@endsection