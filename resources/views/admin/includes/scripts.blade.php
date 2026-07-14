<script src="{{ asset('admin/assets/js/core.js') }} "></script>


<!--

	[SOW Ajax Navigation Plugin] [AJAX ONLY, IF USED]
	If you have specific page js files, wrap them inside #page_js_files 
	Ajax Navigation will use them for this page! 
	This way you can load this page in a normal way and/or via ajax.
	(you can change/add more containers in sow.config.js)

	+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	NOTE: This is mostly for frontend, full ajax navigation!
	Admin Panels use a backend, so the content should be served without
	menu, header, etc! Else, the ajax has no reason to be used because will
	not minimize server load!

	/documentation/plugins-sow-ajax-navigation.html
	+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-->
<div id="page_js_files"><!-- specific page javascript files here --></div>


<!-- <script type="text/javascript" src="{{ ViewHelper::getAssetPath('select2/js/select2.min.js','plugins') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">var plugin_path = "{{ asset('admin/assets/plugins') }}/";</script>
<script type="text/javascript" src="{{ ViewHelper::getAssetPath('bootstrap.daterangepicker/moment.js','plugins') }}"></script>
<script type="text/javascript" src="{{ ViewHelper::getAssetPath('ckeditor/ckeditor.js','plugins') }}"></script>
<script type="text/javascript" src="{{ ViewHelper::getAssetPath('js/editor.js','plugins') }}"></script>
<script type="text/javascript" src="{{ ViewHelper::getAssetPath('dropzone/dropzone.js','plugins') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">
	$(document).ready(function(){

        $('.displayInModal').on('click', function (e){
            data_type = $(this).attr('data-type');
            if(data_type == 'pdf-data')
            {
                src_url = $(this).attr('url');
                $('#modal-show-pdf iframe').attr('src', src_url); 
                $('#modal-show-pdf').modal('show'); 
            }
            else
            {
                src = $(this).attr('src');
                $('#modal_image').attr('src', src); 
                $('#modal-show-image').modal('show'); 
            }

        });
        $('#modal-show-pdf').on('hidden.bs.modal', function () {
            // Reset the data and src attributes of the object and iframe elements
            $('#modal-show-pdf object').attr('data', '');
            $('#modal-show-pdf iframe').attr('src', '');
        });

	});

	jQuery(document).ready(function($) {
        
        $('.select2').select2();

        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });



    $(document).ready(function () {
        $('.confirm-delete').on('click', function (e) {
            var $this = $(this);
            Swal.fire({
                title: 'Do you want to delete?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                html: false
            }).then((result) => {
  				if (result.value) {
                    $this.closest('span').find('form').submit();
                }
            })
        })

        $('.confirm-restore').on('click', function (e) {
            var $this = $(this);
            Swal.fire({
                title: 'Do you want to restore?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!',
                html: false
            }).then((result) => {
                if (result.value) {
                    $this.closest('span').find('form').submit();
                }
            })
        })

        $('.confirm-force-delete').on('click', function (e) {
            var $this = $(this);
            Swal.fire({
                title: 'Do you want to delete permanently?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it permanently!',
                html: false
            }).then((result) => {
                if (result.value) {
                    $this.closest('span').find('form').submit();
                }
            })
        })

    })


    $(document).ready( function () {
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
    $('.bulk_list').click(function (e) {

        var list_id = this.id;

        Swal.fire({
            title: "Are you sure to "+ list_id +" selected rows?",
            showCancelButton: true,
            confirmButtonText: "Yes "+ list_id + " selected rows",
            html: false,
        }).then((result) => {
            if (result.value) {
                var ids = '';
                $('#item_list').find('input:checkbox').each(function (i, v) {
                    if ($(v).is(':checked')) {
                        ids = ids + $(v).val() + ',';
                    }
                });
                $('.row_ids').val(ids);
                $('.bulk_action').val(list_id);
                $('#bulk-action-form').submit();
            }
        })
    });
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

    
    function getAllMedia (){
        $.ajax({
            url: "{!! route('admin.get-media') !!}",
            type: 'GET',
            dataType: 'json',
            data : { 'site_lang' : $('html').attr('lang'), },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(data) {
                $('.gallery-container').html(data.view);
            },
            error: function(data){ 
            }
        });
    }
    $('#media_list_tab').click(function() {
        getAllMedia();
    });
    getAllMedia();
    $(function () {
    $("#mediaModal").on("shown.bs.modal", function (e) {

        const trigger = $(e.relatedTarget);
        const isMultiple = trigger.data("multiple") ?? false;
        const type = trigger.data("type") ?? 'image';
        const field = trigger.data('field');

            $("#mediaModal").on("click", ".gallery-item", function (e) {
                if (isMultiple) {
                    $(this).toggleClass("gallery-image-active");
                } else {
                    $(".gallery-item").removeClass("gallery-image-active");
                    $(this).addClass("gallery-image-active");
                }
            });


            $(".gallery-container").on(
                "click",
                ".media-pagination a",
                function (e) {
                    e.preventDefault();
                    const url = e.target.href;
                    getImages(url);
                }
            );

            $("#media-apply").on("click", function (e) {
                const editor = $('#mediaModal').data('editor');
                if(editor){
                    const instance = CKEDITOR.instances[editor];
                    $(".gallery-image-active").each(function (e) {
                        const val = $(this).find('img').attr("src");
                        // const val = encodeURIComponent(src);
                        instance.insertHtml(
                            "<img src='"+val+"'>"
                        );

                    });
                }else{
                    const container = trigger.parent().find(".preview-container");
                    const input_container = trigger
                        .siblings(".input-container")
                        .empty();
                    container.empty();
                    $(".gallery-image-active").each(function (e) {
                        const clone = $(this)
                            .find("img")
                            .clone()
                            .removeClass("gallery-image")
                            .addClass("preview-item");
                        container.append(clone);
                        const val = $(this).data("id");
                        input_container.append(
                            `<input type="hidden" name="${field}" value=${val}>`
                        );
                    });
                }
                $("#nav-uploads").trigger("click");
                $("#mediaModal").modal("hide");
            });
        });
        $("#mediaModal").on("hidden.bs.modal", function (e) {
            $("#media-apply").off("click");
            $("#nav-selector-tab").off("click");
            $("#media-uploader").off("submit");
            $('#mediaModal').removeData('editor');
            $("#mediaModal").off('click');
        });
    });
    
</script>
<script type="text/javascript" src="{{ asset('/packages/print_any_part/dist/jQuery.print.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        $("#printBtn").on('click', function() {
            $.print("#printable");
        });
    });
</script>
@yield('js_scripts')
@yield('js_libraries')