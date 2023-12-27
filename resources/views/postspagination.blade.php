<div class="row">
    @foreach($all_posts as $all_post)
    <div class="col-md-3">
        <div class="p-3">
            <div class="h-75">
                <img src="/images/posts/{{$all_post->featured_image}}" class="zoom-block img-fluid" alt="{{$all_post->alt_text}}">
            </div>
            <h4 class="mt-2"><a class="link-secondary" href="/blog/{{$all_post->slug}}">{{substr(strip_tags($all_post->title), 0, 100) }}</a></h4>
            {!! substr(strip_tags($all_post->blog_content), 0, 150) !!}... <br><br>
        </div>
    </div>
    @endforeach
</div>
<div id="pagination_links" class="pagination justify-content-center">
    {{ $all_posts->links('pagination::bootstrap-5') }}
</div>