@extends('admin.includes.layout')

@section('content')
    @include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=> App::isLocale('en') ? 'Create' : 'फारम'
    ])
    <section class="rounded mb-3">
        @include('admin.includes.flash-notification')
        <div class="col-md-12">
            <!-- <a href="{{ url('/gunaso/gunaso') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fi fi-go-back" aria-hidden="true"></i> Back</button></a> -->

            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <strong>DROP FILE UPLOAD</strong>
                </div>

                <div class="card-body">

                
                {{ html()->form('POST')->route($base_route.'.store')->class('dropzone nomargin dz-clickable')->id('my-dropzone')->acceptsFiles()->open() }}

                    <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                {{ html()->form()->close() }}
                <small>(File size limit upto: <code>{{ ViewHelper::parse_size(ini_get('upload_max_filesize'))/1024 }} MB</code>)</small>

                </div>

            </div>
        </div>
    </section>
@endsection
@section('js_scripts')
    <script type="text/javascript" src="{{ ViewHelper::getAssetPath('dropzone/dropzone.js','plugins') }}"></script>
<script>

$(document).ready(function(){

    Dropzone.options.myDropzone = {
        maxFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function() {
            this.on("addedfile", function(file) {
                // Create the remove button
                var removeButton = Dropzone.createElement("<button class='btn btn-sm btn-default fullwidth margin-top-10'>Remove file</button>");

                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                  // Make sure the button click doesn't submit the form:
                  e.preventDefault();
                  e.stopPropagation();

                  // Remove the file preview.
                  _this.removeFile(file);
                  // If you want to the delete the file on the server as well,
                  // you can do the AJAX request here.
                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            });
        }
    }

});
</script>
@endsection
