<fieldset>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="category_name">Blog Category Name: <span class="text-danger">*</span></label>
            {{ html()->text('category_name')->class('form-control form-control-sm category_name')->required(true) }}

        </div>

        <div class="form-group col-md-6">
                <label for="slug">Slug: <span class="text-danger">*</span></label>
                {{ html()->text('slug')->class('form-control form-control-sm blog_cat_name_slug')->required(true) }}
                @include('admin.includes.form_validation_alert', ['field' => 'slug'])
            </div>

        <div class="form-group col-md-4 col-sm-4">
            {{ html()->label('Status', 'status')->class(' control-label') }} <br>
            <label class="radio">
                <input type="radio" name="status" value="1" {{ isset($data['request']['status']) && $data['request']['status']==1 ? 'checked' : (isset($data['row']['status']) && $data['row']['status']==1 ? 'checked': 'checked') }}>

                <i></i>Active

            </label>
            <label class="radio">
                <input type="radio" name="status" value="0" {{ isset($data['request']['status']) && $data['request']['status']==0 ? 'checked' : (isset($data['row']['status']) && $data['row']['status']==0 ? 'checked':'') }}>

                <i></i>Inactive
            </label>
        </div>
        <div class="form-group col-md-4">
            <label for="icon"> Category Icon :</label>
            {{ html()->text('icon')->class('form-control form-control-sm') }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="submit" name="submit" class="btn btn-success"> {{ $button }} </button>

            <a type="button" href="{{ route($base_route.'.index') }}"
               class="btn btn-danger row-edit">
                Cancel
            </a>
        </div>
    </div>
</fieldset>

@section('post_scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            var ajax_running = false;
            $('.category_name').keyup(function () {
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
                            $('.blog_cat_name_slug').val(response.slug);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
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