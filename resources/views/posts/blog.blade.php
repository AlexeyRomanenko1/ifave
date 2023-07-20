@include('layouts.app')
<div class="container">
    <!-- <div class="text-center">
        <button class="btn btn-primary mb-5">Create Blog</button>
    </div> -->
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_questions" class="form-control" placeholder="Search for blogs">
                <div class="set_suggestion_height mt-3 d-none">

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @foreach($posts as $post)
        <div class="row border border-blue mt-3 p-2 m-2">
            <div class="col-md-3">
                <img src="/images/posts/{{$post->featured_image}}" class="img-fluid" height="300px" width="300px" alt="">
            </div>
            <div class="col-md-9">
                <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{$post->title}}</a></h4>
                {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
                <small><b>Date:</b> {{$post->created_at}}</small><br>
                <small><b>Author:</b> {{$post->name}}</small>
            </div>
        </div>
        @endforeach
        <div class="pagination justify-content-center">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@include('footer.footer')