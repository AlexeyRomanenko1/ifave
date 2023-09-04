@foreach($posts as $post)
<div class="row border border-blue mt-3 p-2 m-2">
    <div class="col-md-3">
        <img src="/images/posts/{{$post->featured_image}}" class="img-fluid" height="300px" width="300px" alt="">
    </div>
    <div class="col-md-9">
        <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{$post->title}}</a></h4>
        {!! substr(strip_tags($post->blog_content), 0, 300) !!}... <br><br>
        <small> {{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
        <small> {{$post->name}}</small>
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
<div class="pagination justify-content-center">
    {{ $posts->links('pagination::bootstrap-5') }}
</div>