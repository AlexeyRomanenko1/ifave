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
                if (width !== 90 && height !== 120) {
                    toastr.error('Image size should be 90 x 120 pixels. Provided size is ' + height + ' x ' + width);
                    $("#formFile").val("");
                }
            });
        };

        // Read the selected image as a data URL
        reader.readAsDataURL(this.files[0]);
    }
});