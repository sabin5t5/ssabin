{{ html()->form('POST')->route($base_route.'.update')->acceptsFiles()->open() }} 
<input type="hidden" name="form-name" value="{{ $data['requests']['form-name'] }}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                {{ html()->label('Custom Css', 'custom_css')->class('control-label') }}
                {{ html()->textarea('custom_css', isset($data['rows']['custom_css'])?$data['rows']['custom_css'][0]->config_values:'')->placeholder('Custom Css')->class('form-control form-control-sm') }}
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
{{ html()->form()->close() }}