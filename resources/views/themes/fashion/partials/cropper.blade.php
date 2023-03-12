<img  data-yk="" alt="" class="actualImage" src="{{$image ?? ''}}" style="display:none;">
<input type="hidden" name="actualImage" >
<img data-yk="" class="user croppedImage" src="{{$cropped ?? ''}}" alt="">
@section('css')
@parent
<link rel="stylesheet" href="{{ asset('vendors/css/cropper.css') }}">
@endsection
@section('scripts')
@parent
<script src="{{ asset('vendors/js/cropper.js') }}"></script>
<script src="{{ asset('vendors/js/jquery-cropper.min.js') }}"></script>
<script>
    var postUrl = '{{$url}}';
    var aspectRatio = '{{$aspectRatio ?? 1}}';
    $(document).on('click', '.yk-common-cropper .js-openCropper', function(e){
        window.tmppath = '';
        window.actualImage = '';
        window.actualImage = $(this).closest('.yk-common-cropper').find('img.actualImage').attr('src');
        $(this).closest('.yk-common-cropper').find('#modal_cropper input[type="file"]').val('');

        if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
            $image.cropper('destroy');
        }

        if (window.actualImage == '') {
            $('#modal_cropper .js-cropperSelectImage').trigger('click');
            return;
        }

        $('#modal_cropper').modal('show');
        $('.cropperSelectedImage').attr('src', window.actualImage);
        setTimeout(function() {
            loadCropper();
            window.tmppath = window.actualImage;
            $image.cropper('replace', window.actualImage)
        }, 200);
    });
    $(document).on('change', '.js-cropperSelectImage', function(e){
        if(e.target.files.length > 0){
            selectImage(e.target.files[0]);
        }  
    });

    saveCropper = function(event){
        var cropped =  $image.cropper("getCroppedCanvas", { width: 250, height: 250 });
        cropped.toBlob((croppedBlob) => {
            window.cropperImage = croppedBlob;
            let thisObj = $(event);
            let formData = new FormData();
            formData.append('actualImage', window.actualImage);
            formData.append('cropImage', window.cropperImage);
            $.ajax({url: postUrl,
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.YK-userAvatar').attr('src', response.data.thumbURL);
                    $('.yk-common-cropper').find('.croppedImage').attr('src', response.data.thumbURL);
                    $('.yk-common-cropper').find('.actualImage').attr('src', response.data.imageURL);
                    $('#modal_cropper').modal('hide');
                    $image.cropper('destroy');
                }
            });
        }, 'image/png', 0.80);
    }
    loadCropper = function(){
        $image = $('.cropperSelectedImage');
        $image.cropper({ aspectRatio: aspectRatio });
    }
    selectImage = function(img) {
        if (img.size > 2 * 1024 * 1024) {
            toastr.error(imageSizeValidationMessage);
            document.querySelector('.js-cropperSelectImage').value = '';
            return;
        }
        window.actualImage = img;
        window.tmppath = URL.createObjectURL(img);
        if (!$('#modal_cropper').hasClass('show')) {
            $('.cropperSelectedImage').attr('src', window.tmppath);
            $('#modal_cropper').modal('show');
            setTimeout(function() {
                loadCropper();
            }, 200);
        } else {
            $image.data('cropper').replace(window.tmppath);
        }
    }
    </script>
    @endsection
