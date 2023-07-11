@include('layouts.app')
<div class="container">
    <div class="text-center">
        <h4 class="text-decoration-underline">Create Blog</h4>
    </div>
    <form id="blog_form" method="POST" action="{{url('/create_blog')}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="blog_title" class="form-label">Blog Title</label>
            <input type="text" class="form-control" id="blog_title" name="blog_title" required>
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label><br>
            <input type="text" id="tags" class="form-control" name="tags" data-role="tagsinput" />
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Featured Image</label>
            <input class="form-control" type="file" id="formFile" name="featured_image">
        </div>
        <div class="mb-3">
            <label for="edit" class="form-label">Blog Content</label>
            <textarea class="form-control" name="blog_content" id="edit" rows="3" ></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Blog</button>
    </form>
</div>
@include('footer.footer')