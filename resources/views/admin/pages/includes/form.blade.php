<div class="panel panel-default">
    <div class="panel-body">

        <div class="row">

            <div class="form-group col-md-8 col-sm-8">
                <label for="name">Page Title : <span class="text-danger">*</span></label>
                {{ html()->text('title')->class('form-control form-control-sm title')->required(true) }}
            </div>

            <div class="col-md-4 col-sm-4">
                <label for="page_name">Page Name: <span class="text-danger">*</span></label>
                {{ html()->text('page_name')->class('form-control form-control-sm page_name')->required(true) }}

            </div>

        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="description">Description :</label>
                    {!! html()->textarea('description')->class('editor form-control from-control-sm')->id('description') !!}
                </div>
            </div>
        </div>
        <div class="row">

            <div class="form-group col-md-6 col-sm-12">
                <label for="meta_title">Meta Title:</label>
                {{ html()->text('meta_title')->class('form-control form-control-sm') }}
            </div>

            <div class="form-group col-md-6 col-sm-12">
                <label for="meta_description">Meta Description:</label>
                {{ html()->text('meta_description')->class('form-control form-control-sm') }}
            </div>

            <div class="form-group col-md-6 col-sm-12">
                <label for="meta_keywords">Meta Keywords:</label>
                {{ html()->text('meta_keywords')->class('form-control form-control-sm') }}
            </div>
            <div class="form-group col-md-6 col-sm-12">

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

            <div class="form-group col-md-4 col-sm-4">
                <label for="tag">File | Image:<span class="text-danger">*</span></label>
                @if(isset($data['row']) && $data['row']->image != null)
                <br>
                <div class="" style="width:100%">
                    <?php $pathinfo = pathinfo($data['row']->image); ?>
                        @if($data['row']->image  && ($pathinfo['extension']=='jpg' || $pathinfo['extension']=='JPG' || $pathinfo['extension']=='JPEG' || $pathinfo['extension']=='jpeg' || $pathinfo['extension']=='png'|| $pathinfo['extension']=='PNG' || $pathinfo['extension']=='gif' || $pathinfo['extension']=='GIF'))
                        <img src="{{ asset('images/pages/'.$data['row']->image)}}" class='img-responsive'  width="300">
                        @elseif($data['row']->image && ($pathinfo['extension']=='pdf'))
                            <iframe src="{{asset('/images/pages/'.$data['row']->image)}}" style="width:100%"></iframe>
                        @else
                         <a href="{{ asset('images/pages/'.$data['row']->image)}}" target="_blank">
                            Preview
                        @endif
                        <br>
                    <a class="btn btn-sm btn-danger text-white deletebtn" record_type="image" filename="{{ $data['row']->image}}" record_id="{{ $data['row']->id }}">
                        Delete File
                    </a>
                </div>
                    
                @else
                    <div class="input-group mb-3">
                        <div class="custom-file custom-file-sm custom-file-primary">

                            {{ html()->file('image')->class('custom-file-input')->id('inputGroupFileNp') }}
                            {{ html()->label('Choose file', 'image')->class('custom-file-label') }}
                            
                        </div>
                        <small>(File format should be: <code>{{ implode(",", config('custom.allowedfileExtension')) }}</code> and file size limit upto: <code>{{ ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code>)</small>
                    </div>
                @endif

            </div>
        </div>

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
</div>
<div class="models">
    @include('admin.pages.includes.modal_delete_file')
</div>
@section('post_scripts')

    <script type="text/javascript">

        $(document).ready(function () {
            $(".deletebtn").click(function(e){
                e.preventDefault();
                $record_type = $(this).attr('record_type');
                $filename = $(this).attr('filename');
                $record_id = $(this).attr('record_id');
                $page_name = $(this).attr('page_name');

                var url = '{{ route("admin.pages.deleteFile",["record_id"=>":record_id", "record_type"=>":record_type", "filename"=>":filename"]) }}';


                url = url.replace(':filename', $filename);
                url = url.replace(':record_id', $record_id);
                url = url.replace(':record_type', $record_type);

                $("#filename").text($filename);
                $("#page_name").text($page_name);

                $('#submit_file_delete').attr('href', url);
                $("#modal-delete-file").modal('show');
            });

    });

    </script>
@endsection


