@include('layouts.app')
<div class="container">
    <div class="text-center">
        <h4 class="text-decoration-underline">Create Blog</h4>
    </div>

    <form id="blog_form" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="blog_title" class="form-label">Blog Title <b class="text-danger">*</b></label>
            <input type="text" class="form-control" id="blog_title" name="blog_title" value="{{ old('blog_title') }}" required>
            @error('blog_title')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        @if(isset($topic) && isset($question))
        <div class="mb-3">
            <label for="topic_name" class="form-label">Location</label>
            <input type="text" id="topic_name" class="form-control" name="topic_name" value="{{$topic}}" readonly />
            <input type="hidden" name="topic_id" value="{{$topic_id[0]}}">
        </div>
        <div class="mb-3">
            <label for="question" class="form-label">Category</label>
            <input type="text" id="question" class="form-control" name="question" value="{{$question}}" readonly />
            <input type="hidden" name="question_id" value="{{$question_id[0]}}">

        </div>
        @endif
        <div class="mb-3">
            <label for="tags" class="form-label">Tags<b class="text-danger">*</b></label><br>
            <input type="text" id="tags" class="form-control" name="tags" value="{{ old('tags') }}" data-role="tagsinput" />
            <small id="tags_error" class="text-danger d-none">This fied is required</small>
            @error('tags')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="select_location" class="form-label">Location<b class="text-danger">*</b></label>
                    <select class="custom-select" id="select_location" name="topic_id" aria-label="Select Location">
                        <option selected disabled>Select Location</option>
                        @foreach($topics as $all_topic)
                        <option value="{{$all_topic->id}}">{{$all_topic->topic_name}}</option>
                        @endforeach
                    </select>
                    <small id="location_error" class="text-danger d-none">This fied is required</small>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="select_category" class="form-label">Category<b class="text-danger">*</b></label>
                    <select class="custom-select" id="select_category" name="question_id" aria-label="Select Category" disabled>
                        <option selected disabled>Select Category</option>
                    </select>
                    <small id="category_error" class="text-danger d-none">This fied is required</small>
                </div>
            </div>
        </div>

        <div class="mb-3"></div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Featured Image<b class="text-danger"> ( 1000x1000 )*</b></label>
            <input class="form-control" type="file" id="formFile" name="featured_image" required>
            @error('featured_image')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="content_image_table" class="form-label">Content Images</label>
            <table class="table table-bordered" id="content_image_table">
                <tbody>
                    <tr>
                        <td>File</td>
                        <td>
                            <input class="form-control content_images" type="file" name="content_images">
                        </td>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>
                            <input class="form-control content_images" type="file" name="content_images">
                        </td>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>
                            <input class="form-control content_images" type="file" name="content_images">
                        </td>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>
                            <input class="form-control content_images" type="file" name="content_images">
                        </td>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>
                            <input class="form-control" type="file" name="content_images">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="images_div">
                
            </div>
        </div>
        <div class="mb-3">
            <label for="edit" class="form-label">Blog Content<b class="text-danger">*</b></label>
            <textarea class="form-control" name="blog_content" id="edit" rows="3"></textarea>
            <small id="content_error" class="text-danger d-none">This fied is required</small>
            @error('blog_content')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        <button type="submit" class="btn btn-primary">Add Blog</button>
    </form>
</div>
@include('footer.footer')
<script>
    $(document).ready(function() {
        // Initialize your rich textarea editor plugin here

        // Set the previously submitted value
        var previousValue = "{{ old('blog_content') }}";
        $('#edit').val(previousValue);
    });
</script>