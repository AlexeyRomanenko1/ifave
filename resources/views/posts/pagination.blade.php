<div class="row">
    @foreach($posts as $post)
    <div class="col-md-3">
        <div class="p-3">
            <div class="h-75">
                <img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" alt="">
            </div>
            <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{substr(strip_tags($post->title), 0, 100) }}</a></h4>
            {!! substr(strip_tags($post->blog_content), 0, 150) !!}... <br><br>
            @if(auth()->check())
            @if( auth()->user()->id == $post->user_id)
            <div class="mt-3">
                <a href="/edit/blog/{{str_replace(' ','-',$post->name)}}/{{$post->slug}}/{{$post->id}}" class="btn btn-success">Edit Blog</a>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="pagination justify-content-center">
    {{ $posts->links('pagination::bootstrap-5') }}
</div>