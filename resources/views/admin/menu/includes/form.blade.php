<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-6 col-sm-6">
                        <label for="type">Type: <span class="text-danger">*</span></label>
                        {!! html()->select('menu_type', $data['menu_types'])->class('form-control form-control-sm menu_type')->required(true) !!}
                    </div>
                    <div class="form-group col-md-6 col-sm-6" id="parent_id">
                        <label for="title">Parent Menu: <span class="text-danger">*</span></label>
                        {!! html()->select('parent_id', $data['parents_menus'])->class('form-control form-control-sm') !!}
                    </div>
                    <div class="form-group col-md-6 col-sm-6" id="name_en">
                        <label for="name"><span id="name_en_name">Name English:</span> <span class="text-danger">*</span></label>
                        
                        {{ html()->text('name_en')->class('form-control form-control-sm name_en')}}
                    </div>
                    <div class="form-group col-md-6 col-sm-6" id="name_np">
                        <label for="name"><span id="name_np_name"> Name Nepali:</span> <span class="text-danger">*</span></label>
                        
                        {{ html()->text('name_np')->class('form-control form-control-sm name_np')}}
                    </div>
                    <div class="form-group col-md-6 col-sm-6" id="value">
                        <label for="name"><span id="val_name">Value: </span><span class="text-danger">*</span></label>
                        
                        {{ html()->text('value')->class('form-control form-control-sm value')->required(true) }}
                    </div>
                    <div class="form-group col-md-6 col-sm-6" id="status">
                        {{ html()->label('Status', 'status')->class('col-md-3 control-label') }}
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
                <button type="submit" name="submit" class="btn btn-sm btn-success"> {{ $button }} </button>
                <a type="button" href="{{ route($base_route.'.index') }}"
                class="btn btn-sm btn-danger row-edit">
                    {{ App::isLocale('en') ? 'Cancel' : 'रद्द गर्नुहोस्' }}
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-md shadow-lg-hover transition-all-ease-250 transition-hover-top h-100 border-primary bl-0 br-0 bb-0 bw--2">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="btn row-pill btn-sm bg-gradient-warning b-0 py-1 mb-0 float-start">
                        <i class="fi fi-round-info-full"></i>
                        Note
                    </span>
                </h5><br>
                <p class="card-text">
                    The Default <code>Internal Links</code>
                    <ul>
                        <li>
                            <code>/</code> : Landing Page.
                        </li>
                        <li>
                            <code>loginAdmin</code> : Login Page
                        </li>
                        <li>
                            <code>np</code> : Language Switcher to the Nepali
                        </li>
                        <li>
                            <code>white</code> : Theme Switcher to the Normal Mode
                        </li>
                        <li>
                            <code>dark</code> : Theme Switcher to the Dark Mode
                        </li>
                    </ul>
                    The Pages URL format
                    <code>/pages/{page_name}</code> <br>
                </p>
            </div>
        </div>
    </div>
</div>
@section('post_scripts')

    <script type="text/javascript">
        $(document).ready(function () {

            $value_text_field = '<label for="name"><span id="val_name">Value: </span><span class="text-danger">*</span></label><input class="form-control form-control-sm value " required="required" name="value" type="text">';
            $('.menu_type').on('change', function(){

                var $this = $(this);
                _default();
                var ajax_running = false;
                if($(this).val() == 'page_menu' || $(this).val() == 'news_menu')
                {
                    if (!ajax_running) {
                        ajax_running = true;
                        var id = 'NULL';
                        $.ajax({
                            url: '{{ route($base_route.'.getItems') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                type: $(this).val(),
                                id: id
                            },
                            success: function (response) {
                                $('#value').show();
                                $('#value').html(response);
                                $('#parent_id').show();
                                $('#status').show();
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
                }
                else if($(this).val() == 'internal_link')
                {
                    $('#name_en').show();
                    $('#name_np').show();
                    $('#value').show();
                    $('#value').html($value_text_field);
                    
                    $('#val_name').html('URL');
                    $('#parent_id').show();
                    $('#status').show();
                }
                else if($(this).val() == 'external_link')
                {
                    $('#name_en').show();
                    $('#name_np').show();
                    $('#value').show();
                    $('#value').html($value_text_field);
                    $('#val_name').html('URL');
                    $('#parent_id').show();
                    $('#status').show();
                }
                else
                {
                    _default();
                }
            });
            function _default()
            {
                $('#name_en').hide();
                $('#name_np').hide();
                $('#value').hide();
                $('#parent_id').hide();
                $('#status').hide();
            }

            function getPageItems()
            {
                var ajax_running = false;
                if (!ajax_running) {
                        ajax_running = true;
                        var id = 'NULL';
                        $.ajax({
                            url: '{{ route($base_route.'.getItems') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                type: 'page_menu',
                                id: id
                            },
                            success: function (response) {
                                $('#value').show();
                                $('#value').html(response);
                                $('#parent_id').show();
                                $('#status').show();
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
            }
            function getNewsItems()
            {
                var ajax_running = false;
                if (!ajax_running) {
                    ajax_running = true;
                    var id = 'NULL';
                    $.ajax({
                        url: '{{ route($base_route.'.getItems') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: 'news_menu',
                            id: id
                        },
                        success: function (response) {
                            $('#value').show();
                            $('#value').html(response);
                            $('#parent_id').show();
                            $('#status').show();
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
            }
    });

    </script>
@endsection


