@include('layouts.app')
<div class="container p-4 mb-4">
    <div class="text-center">
        <button class="btn btn-danger p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Import Questions</button>
        <button class="btn btn-warning p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal2">Import Answers</button>
        <button class="btn btn-primary p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal3">Import Images</button>
        <a href="/blog-requests" class="btn btn-dark p-1 m-1">Blog Requests</a>
        <form method="POST" action="{{url('export_users')}}">
            @csrf
            <button type="submit" class="btn btn-success p-1 m-1">Export Users</button>
        </form>
    </div>
</div>
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
            <hr>
            <div class="mt-2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4"><small><b>Author:</b> {{$post->name}}</small></div>
                        <div class="col-md-4"><small class="float-end"><b>Date:</b> {{ date('d-m-Y', strtotime($post->created_at)) }}</small></div>
                        <div class="col-md-4"><small class="float-end"><b>Views:</b> {{ number_format($post->views_count, 0, '.', ',')}}</small></div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        <div class="col-md-4">
            <div class="container">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>APPROVE BLOG</h6>
                    </div>
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approve_blog">
                            Approve Blog
                        </button>
                    </div>
                </div>
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
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="approve_blog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/approve-post')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="post_id" value="{{$post_id}}">
                    <small><b>Are you sure you want to approve this blog</b></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('footer.footer')