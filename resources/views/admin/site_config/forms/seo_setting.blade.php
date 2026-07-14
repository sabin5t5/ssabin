{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }} 
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">

<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
                @if(isset($data['rows']['banner']) && $data['rows']['banner'][0]->config_values)
                {{ html()->label('Selected Logo', 'image')->class('control-label') }}
                <img src="{{ ViewHelper::getImagePath($folder, $data['rows']['banner'][0]->config_values) }}"
                        width="200"/>
                <label class="control-label">Logo</label>
                @endif
                {{ html()->file('image') }}
        </div>
        <div class="form-group mb-3">
            <small class="label label-success">NOTE! <code>Image dimension must be 1200 * 630</code>, 
                Image format should be: <code>jpeg, png, bmp, gif, or svg</code> and size limit upto: <code>{{ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code></small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('Meta Title', 'meta_title')->class('control-label') }}
            {{ html()->text('meta_title', isset($data['rows']['meta_title']) ? $data['rows']['meta_title'][0]->config_values : '')->placeholder('Enter Meta Title')->class('form-control form-control-sm') }}
        </div>            
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('Meta Description', 'meta_description')->class('control-label') }}
            {{ html()->text('meta_description', isset($data['rows']['meta_description']) ? $data['rows']['meta_description'][0]->config_values : '')->placeholder('Enter Meta Description')->class('form-control form-control-sm') }}
        </div>           
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            {{ html()->label('Meta Keywords', 'meta_keywords')->class('control-label') }}
            {{ html()->text('meta_keywords', isset($data['rows']['meta_keywords']) ? $data['rows']['meta_keywords'][0]->config_values : '')->placeholder('Enter Meta Keywords')->class('form-control form-control-sm') }}
            <small class="label label-success">Keyword should written by seperatig comma; Example:<code>keyword1, keyword2, keyword3</code></small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('Google Analytics Script', 'google_analytic_script')->class('control-label') }}
            {{ html()->textarea('google_analytic_script', isset($data['rows']['google_analytic_script'])?$data['rows']['google_analytic_script'][0]->config_values:'')->placeholder('Enter Google Analytics Script')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            {{ html()->label('Being/Yahoo Script', 'being_script')->class('control-label') }}
            {{ html()->textarea('being_script', isset($data['rows']['being_script']) ? $data['rows']['being_script'][0]->config_values : '')->placeholder('Enter Being/Yahoo Script')->class('form-control form-control-sm') }}

        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-3">
            {{ html()->label('Schema Markup', 'schema_markup')->class('control-label') }}
            {{ html()->textarea('schema_markup',isset($data['rows']['schema_markup']) ? $data['rows']['schema_markup'][0]->config_values : '')->placeholder('Enter Schema Markup')->class('form-control form-control-sm') }}
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