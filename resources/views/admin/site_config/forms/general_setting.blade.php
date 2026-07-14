{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }}
        
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('First Name', 'first_name')->class('control-label') }}
                    {{ html()->text('first_name', isset($data['rows']['first_name']) ? $data['rows']['first_name'][0]->config_values : '')->placeholder('Government Name(English)')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('Last Name', 'last_name')->class('control-label') }}
                    {{ html()->text('last_name', isset($data['rows']['last_name']) ? $data['rows']['last_name'][0]->config_values : '')->placeholder('Office Name(नेपालि)')->class('form-control form-control-sm') }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('Address', 'address')->class('control-label') }}
                    {{ html()->text('address', isset($data['rows']['address'])?$data['rows']['address'][0]->config_values : '')->placeholder('Office Address(English)')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('Living Address', 'living_address')->class('control-label') }}
                    {{ html()->text('living_address', isset($data['rows']['living_address'])?$data['rows']['living_address'][0]->config_values : '')->placeholder('Office Address(नेपालि)')->class('form-control form-control-sm') }}
                </div>
            </div>
        </div>  
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    @if(isset($data['rows']['logo']) && $data['rows']['logo'][0]->config_values)
                    {{ html()->label('Selected Logo', 'image')->class('control-label') }}
                    <img src="{{ ViewHelper::getImagePath($folder, $data['rows']['logo'][0]->config_values) }}"
                         width="200"/>
                    <label class="control-label">Logo</label>
                    @endif
                    {{ html()->file('image') }}

                </div>
            </div>
            <div class="form-group mb-3">
                <small class="label label-success">NOTE! <code>Image dimension must be 50*50</code>, 
                    Image format should be: <code>jpeg, png, bmp, gif, or svg</code> and size limit upto: <code>{{ ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code></small>

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {{ html()->label('Domain Name', 'domain')->class('control-label') }}
                    {{ html()->text('domain', isset($data['rows']['domain'])?$data['rows']['domain'][0]->config_values : '')->placeholder('Domain Name')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {{ html()->label('Email', 'email')->class('control-label') }}
                    {{ html()->text('email', isset($data['rows']['email'])?$data['rows']['email'][0]->config_values : '')->placeholder('Enter Email')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {{ html()->label('Phone Number', 'phone_en')->class('control-label') }}
                    {{ html()->text('phone_en', isset($data['rows']['phone_en'])?$data['rows']['phone_en'][0]->config_values : '')->placeholder('Phone Number')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    {{ html()->label('Fax', 'fax_en')->class('control-label') }}
                    {{ html()->text('fax_en', isset($data['rows']['fax_en'])?$data['rows']['fax_en'][0]->config_values : '')->placeholder('Fax')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    {{ html()->label('Map', 'map')->class('control-label') }}
                    {{ html()->text('map', isset($data['rows']['map'])? $data['rows']['map'][0]->config_values: '')->placeholder('Enter map link')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('Play Store Link', 'playstore')->class('control-label') }}
                    {{ html()->text('playstore', isset($data['rows']['playstore'])? $data['rows']['playstore'][0]->config_values: '')->placeholder('Enter playstore link')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    {{ html()->label('App Store Link', 'appstore')->class('control-label') }}
                    {{ html()->text('appstore', isset($data['rows']['appstore'])? $data['rows']['appstore'][0]->config_values: '')->placeholder('Enter appstore link')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('Facebook Link', 'facebook')->class('control-label') }}
                    {{ html()->text('facebook', isset($data['rows']['facebook'])? $data['rows']['facebook'][0]->config_values: '')->placeholder('Enter facebook link')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('Twitter Link', 'twitter')->class('control-label') }}
                    {{ html()->text('twitter', isset($data['rows']['twitter'])? $data['rows']['twitter'][0]->config_values: '')->placeholder('Enter twitter link')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('Skype Number', 'skype')->class('control-label') }}
                    {{ html()->text('skype', isset($data['rows']['skype'])? $data['rows']['skype'][0]->config_values: '')->placeholder('Enter skype number')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('Viber Number', 'viber')->class('control-label') }}
                    {{ html()->text('viber', isset($data['rows']['viber'])? $data['rows']['viber'][0]->config_values: '')->placeholder('Enter viber number')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('IMO Number', 'imo')->class('control-label') }}
                    {{ html()->text('imo', isset($data['rows']['imo'])? $data['rows']['imo'][0]->config_values: '')->placeholder('Enter imo number')->class('form-control form-control-sm') }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    {{ html()->label('Toll Free Number', 'toll_free')->class('control-label') }}
                    {{ html()->text('toll_free', isset($data['rows']['toll_free'])? $data['rows']['toll_free'][0]->config_values: '')->placeholder('Enter toll free number')->class('form-control form-control-sm') }}
                </div>
            </div>
        </div>
        @can('update-site configuration')
        <div class="row">
            <div class="form-group mb-3">
                    <div class="col-md-12">
                        <button type="submit" name="submit"  class="btn btn-info btn-sm">Update </button>
                    </div>
            </div>
        </div>
        @endcan
    </div>
</div>
{{ html()->form()->close() }}
