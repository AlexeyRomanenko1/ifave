(function () {
    // $('#edit').richText();
    var uploadUrl = $('#edit').data('upload-url');
    new FroalaEditor("#edit", {
        key: "OXC1lD4B3B14B10D6D6C5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5H5A1D3A11A3D5F4==",
        theme: 'dark',
        toolbarButtons: {
            // Customize the buttons you want to show in the toolbar.
            // Remove 'insertVideo' to exclude it. Keep 'insertImage'.
            moreText: {
                buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor']
            },
            moreParagraph: {
                buttons: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
            },
            moreRich: {
                buttons: ['insertImage', 'insertLink', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters']
            },
            moreMisc: {
                buttons: ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
            }
        },
        imageUploadURL: uploadUrl,
        imageUploadMethod: 'POST', // Set the method to POST
        imageUploadParams: {
            _token: $('meta[name="csrf-token"]').attr('content') // Pass the CSRF token if required
        }
    })
    // $('#imageManager-1').addClass('d-none');
    // $("#select_location").customselect();
    // $("#select_location").select2();
    $("#select_location").select2({
        ajax: {
            url: '/search_blog_cat', // Replace with your Laravel route URL
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // Search term from user input
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // console.log(data)
                var options = [];

                // Assuming data.items is an array of objects with "id" and "text" properties
                if (data) {
                    options = data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.topic_name
                        };
                    });
                }

                return {
                    results: options
                };
            },

            cache: true,
            error: function (error) {
                // console.error(error);
            }
        },
        placeholder: 'Search...',
    });
    $("#select_category").select2();
    $('input')
        .on('change', function (event) {
            var $element = $(event.target);
            var $container = $element.closest('.example');

            if (!$element.data('tagsinput')) return;

            var val = $element.val();
            if (val === null) val = 'null';
            var items = $element.tagsinput('items');

            $('code', $('pre.val', $container)).html(
                $.isArray(val)
                    ? JSON.stringify(val)
                    : '"' + val.replace('"', '\\"') + '"'
            );
            $('code', $('pre.items', $container)).html(
                JSON.stringify($element.tagsinput('items'))
            );
        })
        .trigger('change');
})()

// $('#blog_form').on('submit',function(e){
//     e.preventDefault();
//     if($('#tags').val()==''){
//         toastr.warning('Tags can not be empty');
//     }
//     if($('#edit').val()==''){
//         toastr.warning('Blog content can not be empty');   
//     }
// })

$('#select_location').on('change', function () {
    $.ajax({
        type: 'POST',
        url: '/get_categories_onchange',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), topic_id: $(this).val() },
        success: function (data) {
            // $("#msg").html(data.msg);
            // console.log(data)
            let obj = JSON.parse(data);
            let html = '  <label for="select_category" class="form-label">Category<b class="text-danger">*</b></label> <select class="custom-select custom-select-category" id="select_category" name="question_id" aria-label="Select Category"><option selected disabled>Select Category</option>';

            // let ul = '<li class="active">Select Category</li>';
            for (let j = 0; j < obj.data.length; j++) {
                html += '<option value="' + obj.data[j]['id'] + '">' + obj.data[j]['question'] + '</option>';
                // ul = '<li class="active" data-value="' + obj.data[j]['id'] + '">' + obj.data[j]['question'] + '</li>';
            }
            html += '</select>';
            $("#select_category").attr("disabled", false);
            $('#custom-select-category').empty();
            $('#custom-select-category').html(html);
            // $("#select_category").customselect();
            $("#select_category").select2();
        },
        error: function (e) {
            console.log(e)
        }
    });
})

// Bind the change event to the file input field
$("#formFile").on('change', function () {
    // Check if a file is selected
    if (this.files && this.files[0]) {
        // Create a new FileReader
        var reader = new FileReader();

        // Set the onload event for the FileReader
        reader.onload = function (e) {
            // Create a new Image object
            var image = new Image();

            // Set the source of the Image object to the data URL of the selected image
            image.src = e.target.result;

            // Attach an onload event to the Image object
            $(image).on('load', function () {
                // Get the image dimensions
                var width = this.width;
                var height = this.height;

                // Display the dimensions (you can do whatever you want with them)
                if (width !== 600 && height !== 600) {
                    toastr.error('Image size should be 600 x 600 pixels. Provided size is ' + height + ' x ' + width);
                    $("#formFile").val("");
                }
            });
        };

        // Read the selected image as a data URL
        reader.readAsDataURL(this.files[0]);
    }
});


$('#blog_form').on('submit', function (e) {
    e.preventDefault();
    let error_count = 0;
    if ($('#edit').val() == '' || $('#edit').val().length < 100) {
        $('#content_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#content_error').addClass('d-none');
    }
    if ($('#tags').val() == '') {
        $('#tags_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#tags_error').addClass('d-none');
    }
    if ($('#select_location').val() == '' || $('#select_location').val() == null) {
        $('#location_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#location_error').addClass('d-none');
    }
    if ($('#select_category').val() == '' || $('#select_category').val() == null) {
        $('#category_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#category_error').addClass('d-none');
    }
    if ($('#alt_text').val() == '' || $('#alt_text').val() == null) {
        $('#alt_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#alt_error').addClass('d-none');
    }
    if (error_count > 0) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/create_blog',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            // $("#msg").html(data.msg);
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
            if (obj.success == 0) {
                toastr.error(obj.data);
            }
            if (obj.success == 3) {
                toastr.error('Please verify your account in order to add blogs')
            }

        },
        error: function (e) {
            console.log(e)
        }
    });
})

// function get_selected_image(x) {
$('.content_images').on('change', function (e) {
    var formData = new FormData();

    // Get all the files so we easily can get the length etc.
    // Not necessary, but it will make the code easier to read.
    formData.append('content_images', $(this)[0].files[0]);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    console.log(formData);
    // return;
    $.ajax({
        url: "/upload_content_image",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            console.log(data);
            var obj = JSON.parse(data);
            let html = '';
            // invalid file format.
            if (obj.success == 1) {
                html += '<div class="row mt-5"><div class="col-md-3"><img src="' + obj.path + '" heigth="100px" width="100px"></img></div><div class="col-md-9"><p><b>URL:</b></p><p onclick="copy_image_path(\'' + obj.path + '\')">' + obj.path + '</p></div></div>';
                $('.images_div').append(html);
            } else {
                toastr.error(obj.data);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
})
$('#blog_title').on('keyup', function () {
    $('.title_count').empty();
    $('.title_count').html($(this).val().length + '/100');
})
$('.unfold_instructions').on('click', function () {
    $('.instruction-fold').removeClass('d-none')
    $(this).addClass('d-none')
})
function copy_image_path(x) {
    navigator.clipboard.writeText(x);
    toastr.success('Link copied!')
}



