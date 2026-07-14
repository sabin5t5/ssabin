<fieldset>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="title_en">Video Title | English: <span class="text-danger">*</span></label>
            {{ html()->text('title_en')->class('form-control form-control-sm title_en')->required(true)  }}

        </div>

        <div class="form-group col-md-4">
            <label for="title_np">Video Title | Nepali: <span class="text-danger">*</span></label>
            {{ html()->text('title_np')->class('form-control form-control-sm title_np')->required(true)  }}

        </div>

        <div class="form-group col-md-4">
                <label for="slug">Slug: <span class="text-danger">*</span></label>
                {{ html()->text('slug')->class('form-control form-control-sm section_name_slug')->required(true)  }}
                @include('admin.includes.form_validation_alert', ['field' => 'slug'])
            </div>

        <div class="form-group col-md-6">
            <label for="video_link">Video Link: <span class="text-danger">*</span></label>
            https://www.youtube.com/watch?v=
                {{ html()->text('video_link')->class('form-control form-control-sm video_link')->placeholder('ybcgf45XrUQ')->required(true)  }}
        </div>

    
        <div class="form-group col-md-3 col-sm-3">
            <div class="video"></div>
            @if(isset($data['row']))
                {{ html()->label('Existing Videos', 'file')->class('control-label') }}
                @if (isset($data['row']->video_link))
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{$data['row']->video_link}}" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <p>No Image</p>
                @endif
            @endif
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12 col-sm-12">
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

    <div class="row">
        <div class="col-md-6">
            <button type="submit" name="submit" class="btn btn-sm btn-success"> {{ $button }} </button>
            <a type="button" href="{{ route($base_route.'.index') }}"
               class="btn btn-sm btn-danger row-edit">
                {{ App::isLocale('en') ? 'Cancel' : 'रद्द गर्नुहोस्' }}
            </a>
        </div>
    </div>
</fieldset>

@section('post_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var ajax_running = false;
            $('.video_link').keyup(function()
            {
                var video_link = $(this).val();
                var iframe = '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/'+video_link+'">';
                $('.video').html(iframe);
            });

            $('.title_en').keyup(function () {
                if (!ajax_running) {
                    ajax_running = true;
                    var id = 'NULL';
                    @if (isset($data['row']))
                        id = {{ $data['row']->id }}
                    @endif
                    $.ajax({
                        url: '{{ route($base_route.'.generate.slug') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            title: $(this).val(),
                            id: id
                        },
                        success: function (response) {
                            $('.section_name_slug').val(response.slug);
                        },
                        error: function (request, status, error) {
                            $.notify({
                                // options
                                message: request.responseText
                            }, {
                                // settings
                                type: 'danger'
                            });
                        },
                        complete: function () {
                            ajax_running = false;
                        }
                    });

                }

            });
    });

    </script>
@endsection