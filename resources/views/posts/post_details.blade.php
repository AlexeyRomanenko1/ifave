@include('layouts.app')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @foreach($posts as $post)
            <input type="hidden" id="post_id" value="{{$post->id}}">
            <input type="hidden" id="post_vote_count" value="{{$post->vote_count}}">
            <input type="hidden" id="post_down_votes" value="{{$post->down_votes}}">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{$post->title}}</h2>
                <div class="d-flex">
                    <div class="text-center mx-3">
                        <i class="fa fa-2x fa-thumbs-up text-success post-thumbs-up" aria-hidden="true"></i>
                        <div><small>Likes: {{ number_format($post->vote_count,0,'.',',') }}</small></div>
                    </div>
                    <div class="text-center mx-3">
                        <i class="fa fa-2x fa-thumbs-down text-danger post-thumbs-down" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <img src="/images/posts/{{$post->featured_image}}" class="img-fluid mt-3" alt="...">
            </div>
            <div class="mt-3">
                {!! $post->blog_content !!}
            </div>
            <div class="mt-2">
                <small><b>Author:</b> {{$post->name}}</small><br>
                <small><b>Date:</b> {{$post->created_at}}</small><br>
                <small><b>Views:</b> {{ number_format($post->views_count, 0, '.', ',')}}</small>
            </div>
            @endforeach

        </div>
        <div class="col-md-4">
            <div class="container">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>CONNECT & FOLLOW</h6>
                    </div>
                    <div class="card-body text-center">
                        <a id="" class="btn  m-2" href=""><i class="fa fa-facebook-square" aria-hidden="true"></i></a><a id="" class="btn m-2" href=""><i class="fa fa-twitter-square" aria-hidden="true"></i></a><a href="" class="btn m-2"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>NEWSLETTER</h6>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Enter your email address below to subscribe to my newsletter</p>
                        <form action="">
                            <input type="email" class="form-control" placeholder="You email address....">
                            <div class="">
                                <button class="btn btn-primary mt-4">SUBSCRIBE</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if(count($latest_posts) > 0)
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>LATEST POSTS</h6>
                    </div>
                    <div class="card-body">
                        @foreach($latest_posts as $lates_post)
                        <div class="row">
                            <div class="col-md-4">
                                <img src="/images/posts/{{$lates_post->featured_image}}" class="mt-2" height="80px" width="80px">
                            </div>
                            <div class="col-md-8">
                                <h4 class="mt-2"><a href="/blog/{{$lates_post->slug}}">{{$lates_post->title}}</a></h4>
                                {!! substr(strip_tags($lates_post->blog_content), 0, 100) !!}... <br><br>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('footer.footer')