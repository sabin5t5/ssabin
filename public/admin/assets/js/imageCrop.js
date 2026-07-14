var Croppie = (function() {

    function demoUpload() {
        var $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.upload-demo').addClass('ready');
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    });

                }

                reader.readAsDataURL(input.files[0]);
            }
            else {
                alert ("Sorry - you're browser doesn't support the FileReader API");
                return 0;
            }
        }

        $dwidth = $( "#crop" ).attr( "data-width" );
        $dheight = $( "#crop" ).attr( "data-height" );
        
        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: $dwidth,
                height: $dheight,
                type: 'square'
            },
            boundary: { width: 'auto', height: 350 },
            enableExif: true
        });

        $('#upload').on('change', function () { readFile(this); });
        $('.upload-result').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport',
                format: 'jpeg'
            }).then(function (resp) {
                $(".preview").empty();
                $('.preview').append('<img src="'+resp+'" class=" thumbnail small-image"><input type="text" class="hidden" name="image" value="'+resp+'">');
                $(".selectImage").addClass('hidden');
                $("#removeimg").removeClass('hidden');
                $('#modal_crop_image').modal('hide');
            });
        });

        $('#myModal').on('hidden.bs.modal', function () {
                $('.upload-demo').removeClass('ready');
                $('#upload').val(''); // this will clear the input val.
                $uploadCrop.croppie('bind', {
                    url : ''
                });
        });
    }

    function init() {
        demoUpload();
    }

    return {
        init: init
    };
})();
