@include('layouts.app')
<div class="container">
    <div class="text-center">
        <h4 class="text-decoration-underline">Create Blog</h4>
    </div>

    <form id="blog_form" method="POST" action="{{url('/create_blog')}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="blog_title" class="form-label">Blog Title <b class="text-danger">*</b></label>
            <input type="text" class="form-control" id="blog_title" name="blog_title" value="{{ old('blog_title') }}">
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
            @error('tags')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Featured Image<b class="text-danger">*</b></label>
            <input class="form-control" type="file" id="formFile" name="featured_image">
            @error('featured_image')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="edit" class="form-label">Blog Content<b class="text-danger">*</b></label>
            <textarea class="form-control" name="blog_content" id="edit" rows="3"></textarea>
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