<div class="panel panel-default">
    <div class="panel-body">

        <div class="row">

            <div class="form-group col-md-6 col-sm-6">
                <label for="published_at_bs">Published From: <span class="text-danger">*</span></label>
                <div class="input-group">
                    {!! html()->text('published_from_bs', isset($data['request']['published_from_bs'])?$data['request']['published_from_bs']:null)->placeholder('Published From')->class('form-control form-control-sm nepalidate-picker')->id('published_from_bs') !!}

                    <div class="input-group-append">
                        <button class="btn btn-danger btn-sm" type="button" id="published_from_clear"><i class="fi fi-close"></i></button>
                    </div>
                    {!! html()->text('published_from', isset($data['request']['published_from']) ? $data['request']['published_from'] : (isset($data['row']['published_from'])? $data['row']['published_from']->format('Y-m-d'):null))->class('hidden')->style('display:none')->id('published_from') !!}

                </div>
            </div>

            <div class="form-group col-md-6 col-sm-6">
                <label for="published_at_bs">Published To: <span class="text-danger">*</span></label>
                <div class="input-group">
                    {!! html()->text('published_to_bs', isset($data['request']['published_to_bs'])?$data['request']['published_to_bs']:null)->placeholder('Published To')->class('form-control form-control-sm nepalidate-picker')->id('published_to_bs') !!}

                    <div class="input-group-append">
                        <button class="btn btn-danger btn-sm" type="button" id="published_to_clear"><i class="fi fi-close"></i></button>
                    </div>
                    {!! html()->text('published_to', isset($data['request']['published_to']) ? $data['request']['published_to'] : (isset($data['row']['published_to'])? $data['row']['published_to']->format('Y-m-d'):null))->class('hidden')->style('display:none')->id('published_to') !!}
                </div>
            </div>

            <div class="form-group col-md-6 col-sm-6">
                <label for="name">Start Up Notice Title English: <span class="text-danger">*</span></label>
               
                {{ html()->text('title_en')->class('form-control form-control-sm title_en')->required(true) }}
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label for="name">Start Up Notice Title Nepali: <span class="text-danger">*</span></label>
                {{ html()->text('title_np')->class('form-control form-control-sm title_np')->required(true) }}
            </div>

        </div>
        <div class="row">
            <div class=" form-group col-md-6 col-sm-6">
                <label for="description_en">Description English:</label>
                {!! html()->textarea('description_en')->class('editor form-control from-control-sm')->id('description_en') !!}

            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label for="description_np">Description Nepali:</label>
                {!! html()->textarea('description_np')->class('editor form-control from-control-sm')->id('description_np') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-4">
                <label for="tag">Image | File :</label>
                <div class="input-group mb-3">
                    <div class="custom-file custom-file-sm custom-file-primary">

                        {{ html()->file('image')->class('custom-file-input')->id('inputGroupFile01') }}
                        {{ html()->label('Choose file', 'image')->class('custom-file-label') }}
                        
                    </div>
                </div>
                <small>(File format should be: <code>{{ implode(",", config('custom.allowedfileExtension')) }}</code> and file size limit upto: <code>{{ ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code>)</small>


            </div>
            @if(isset($data['row']))
                <div class="form-group col-md-6 col-sm-6">
                    {{ html()->label('Existing Image|File', 'file')->class('col-md-3 control-label') }}
                    @if (isset($data['row']->image))
                        <img src="{{ ViewHelper::getImagePath($folder, $data['row']->image) }}" width="300">
                    @else
                        <p>No Image</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-6">

                {{ html()->label('Status', 'status')->class('col-md-2 control-label') }}
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
</div>
<div class="row">
    <div class="col-md-6">
        <button type="submit" name="submit" class="btn btn-success"> {{ $button }} </button>
        
        <a type="button" href="{{ route($base_route.'.index') }}"
           class="btn btn-danger row-edit">
            {{ App::isLocale('en') ? 'Cancel' : 'रद्द गर्नुहोस्' }}
        </a>
    </div>
</div>
</div>

@section('post_scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            if(typeof($("input[name='id']").val()) != "undefined" && $("input[name='id']").val() !== null)
            {
                $('#published_from_bs').val(NepaliFunctions.AD2BS(moment($('#published_from').val()).format("YYYY-MM-DD")));
                $('#published_to_bs').val(NepaliFunctions.AD2BS(moment($('#published_to').val()).format("YYYY-MM-DD")));
            }
            customNepaliDatePicker('published_from');
            customNepaliDatePicker('published_to');

    });

    </script>
@endsection


