<div class="panel panel-default">
    <div class="panel-body">

        <div class="row">

            <div class="form-group col-md-12 col-sm-12">
                <label for="name"> Title : <span class="text-danger">*</span></label>
                {{ html()->text('title')->class('form-control form-control-sm blog_title')->required(true) }}
            </div>
            <div class="col-md-4 col-sm-4">
                <label for="slug">Slug: <span class="text-danger">*</span></label>
                {{ html()->text('slug')->class('form-control form-control-sm blog_slug')->required(true) }}
                @include('admin.includes.form_validation_alert', ['field' => 'slug'])
            </div>

            <div class="form-group col-md-4 col-sm-4">
                <label for="title">Category: <span class="text-danger">*</span></label>
                {!! html()->select('blog_category', $data['blog_categories'])->class('form-control form-control-sm ')->required(true) !!}

            </div>

            <div class="form-group col-md-4 col-sm-4">
                <label for="published_at_bs">Published Date: <span class="text-danger">*</span></label>
                <div class="input-group">
                    {{ html()->text('published_at', isset($data['request']['published_at']) ? $data['request']['published_at'] : (isset($data['row']['published_at'])? $data['row']['published_at']->format('Y-m-d'):null))->class('form-control form-control-sm datepicker')->id('published_at') }}
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="description">Description English:</label>
                    <div class="alert alert-info alert-sm" style="padding: 8px 10px; margin-bottom: 10px;">
                        <small>
                            To add copyable source code or commands, use the buttons below. They insert a styled block that can be copied on the public blog page.
                        </small>
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-xs btn-default insert-copyable-block" data-type="code">Insert copyable code</button>
                        <button type="button" class="btn btn-xs btn-default insert-copyable-block" data-type="command">Insert copyable command</button>
                    </div>
                    {!! html()->textarea('description')->class('editor form-control from-control-sm')->id('description') !!}
                </div>
            </div>
        </div>
        <div class="row"> 
            <div class="form-group col-md-3 col-sm-6">
                <label for="tag">Image | File :</label>
                @if(isset($data['row']) && $data['row']->image != null)
                <br>
                <div class="" style="width:100%">
                    <?php $pathinfo = pathinfo($data['row']->image); ?>
                        @if($data['row']->image  && ($pathinfo['extension']=='jpg' || $pathinfo['extension']=='JPG' || $pathinfo['extension']=='JPEG' || $pathinfo['extension']=='jpeg' || $pathinfo['extension']=='png'|| $pathinfo['extension']=='PNG' || $pathinfo['extension']=='gif' || $pathinfo['extension']=='GIF'))
                        <img src="{{ asset('images/blogs/'.$data['row']->image)}}" class='img-responsive'  width="300">
                        @elseif($data['row']->image && ($pathinfo['extension']=='pdf'))
                            <iframe src="{{asset('/images/blogs/'.$data['row']->image)}}" style="width:100%"></iframe>
                        @else
                         <a href="{{ asset('images/blogs/'.$data['row']->image)}}" target="_blank">
                            Preview
                        @endif
                        <br>
                    <a class="btn btn-sm btn-danger text-white confirm-delete-file" filename="{{ $data['row']->image}}" record_id="{{ $data['row']->id }}">
                        Delete File
                    </a>
                </div>
                    
                @else
                    <div class="input-group mb-3">
                        <div class="custom-file custom-file-sm custom-file-primary">
                            {{ html()->file('image')->class('custom-file-input')->id('inputGroupFile03') }}
                            {{ html()->label('Choose file', 'image')->class('custom-file-label') }}                            
                        </div>
                        <small>(File format should be: <code>{{ implode(",", config('custom.allowedfileExtension')) }}</code> and file size limit upto: <code>{{ ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code>)</small>
                    </div>
                @endif
            </div>
            <div class="form-group col-md-3 col-sm-6">
                <label for="tag">Tags:</label>
                {!! html()->select('tags[]', $data['tags'])->class('form-control form-control-sm select2')->id('tags')->multiple(true) !!}
            </div>
            <div class="form-group col-md-3 col-sm-6">
                <label for="archive_at_bs">Archive Date: </label>
                <div class="input-group">
                    {{ html()->text('archive_at', isset($data['request']['archive_at']) ? $data['request']['archive_at'] : (isset($data['row']['archive_at'])? $data['row']['archive_at']->format('Y-m-d'):null))->class('form-control form-control-sm datepicker')->id('archive_at') }}
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6">
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
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-6 mt-4">
                {{ html()->label('Highlight', 'is_highlight')->class('col-md-4 control-label') }}
                @php
                    // Determine the highlight value based on the old input, $data['row'] value, or default to 0
                    $highlightValue = old('is_highlight', isset($data['row']['is_highlight']) ? $data['row']['is_highlight'] : 0);
                @endphp

                <label class="radio">
                    <input type="radio" name="is_highlight" value="1" {{ isset($data['request']['is_highlight']) && $data['request']['is_highlight']==1 ? 'checked' : (isset($data['row']['is_highlight']) && $data['row']['is_highlight']==1 ? 'checked': 'checked') }}>

                    <i></i>Yes

                </label>
                <label class="radio">
                    <input type="radio" name="is_highlight" value="0" {{ isset($data['request']['is_highlight']) && $data['request']['is_highlight']==0 ? 'checked' : (isset($data['row']['is_highlight']) && $data['row']['is_highlight']==0 ? 'checked':'') }}>

                    <i></i>No
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
            Cancel
        </a>
    </div>
</div>
</div>

@section('post_scripts')

    <script type="text/javascript">
        $(document).ready(function () {

            $('.confirm-delete-file').on('click', function (e) {
                var $this = $(this);
                Swal.fire({
                    title: 'Do you want to delete file permanently?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it permanently!',
                    html: false
                }).then((result) => {
                    if (result.value) {
                        id = $(this).attr('record_id')
                        var url = '{{ route("admin.blogs.deleteFile",["id"=>":record_id"]) }}';
                        url = url.replace(':record_id', id);

                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                filename : $(this).attr('filename'),
                                id : id,
                            },
                            success: function (response) {
                                location.reload();
                            }
                            
                        });
                    }

                        
                })
            })

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            $('.insert-copyable-block').on('click', function (e) {
                e.preventDefault();

                var type = $(this).data('type') || 'code';
                var sampleText = type === 'command'
                    ? 'git clone https://example.com/project.git\ncd project\nnpm install'
                    : 'function greet() {\n    return "Hello";\n}';

                var blockId = 'blog-' + type + '-' + Date.now();
                var html = '<div class="code-block" style="position:relative;margin-top:0.75rem;border:1px solid rgba(255,255,255,0.12);border-radius:0.75rem;overflow:hidden;background:rgba(0,0,0,0.35);">' +
                    '<button type="button" class="code-copy-btn" data-copy-target="' + blockId + '">Copy</button>' +
                    '<pre id="' + blockId + '" style="margin:0;padding:1rem;color:#f3f4f6;white-space:pre-wrap;"><code>' + escapeHtml(sampleText) + '</code></pre>' +
                    '</div>';

                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
                    CKEDITOR.instances.description.insertHtml(html);
                } else {
                    var currentValue = $('#description').val();
                    $('#description').val(currentValue + html);
                }
            });

            var ajax_running = false;
            $('.blog_title').keyup(function () {
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
                            $('.blog_slug').val(response.slug);
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


