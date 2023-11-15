@include('layouts.app')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @foreach($posts as $post)
            <div class="text-center">
            <a href="/category/{{ $post_location_link }}/{{ $post_category }}">Go to ranking of {{ str_replace('-',' ',$post_category) }} in {{ str_replace('-',' ',$post_location_link) }}</a> <br><br>
            </div>
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
                @if($post->alt_text != '' || $post->alt_text != NULL)
                <img src="/images/posts/{{$post->featured_image}}" class="img-fluid mt-3" alt="{{$post->alt_text}}">
                @else
                <img src="/images/posts/{{$post->featured_image}}" class="img-fluid mt-3" alt="{{$post->title}}">
                @endif
            </div>
            <div class="mt-3" id="blog_content">
                {!! $post->blog_content !!}
            </div>
            <div class="d-flex justify-content-between align-items-center">
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
            <div class="mt-3">
                <a href="/category/{{ $post_location_link }}/{{ $post_category }}">Go to ranking of {{ str_replace('-',' ',$post_category) }} in {{ str_replace('-',' ',$post_location_link) }}</a> <br><br>
            </div>
            <hr>
            <div class="mt-2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4"><small><b>Author:</b> <a href="/blogger/{{str_replace(' ','-',$post->name)}}">{{$post->name}}</a></small></div>
                        <div class="col-md-4"><small class="float-end"><b>Date:</b> {{ date('d-m-Y', strtotime($post->created_at)) }}</small></div>
                        <div class="col-md-4"><small class="float-end"><b>Views:</b> {{ number_format($post->views_count, 0, '.', ',')}}</small></div>
                    </div>
                </div>
            </div>
            @if(count($get_comments) > 0)
            @foreach($get_comments as $user_comment)
            @if($user_comment->parent_comment_id == 0)
            <div class="comment-container comment mb-2">
                <div class="comment-content">
                    @if(strlen($user_comment->comments) > 150)
                    <p class="half-comment">{{ substr($user_comment->comments, 0, 150) }} <span class="read-more">... Read More</span></p>
                    <span class="full-comment" style="display: none;">{{ $user_comment->comments }}</span>
                    @else
                    <p>{{ $user_comment->comments }}</p>
                    @endif
                </div>
                <div class="comment-actions">
                    <small class="mt-2">
                        @if($user_comment->name != '')
                        <b>{{$user_comment->name}}</b>
                        @else
                        <b>Anonymous</b>
                        @endif
                        ({{$user_comment->upvotes}} Upvotes)</small>
                    <div>
                    </div>
                </div>
                <a href="#" class="reply-btn" data-comment-id="{{ $user_comment->id }}">Reply</a>
                <form method="POST" action="{{ route('postscomments.storeReplyPost') }}" class="reply-form reply-form-{{ $user_comment->id }} mt-3" style="display: none;">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{$post->id}}">
                    <input type="hidden" name="parent_comment_id" value="{{ $user_comment->id }}">
                    <textarea name="reply_text" class="form-control" rows="3" placeholder="Reply to this comment"></textarea>
                    <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                </form>
                <hr>
                @foreach($replies as $reply)
                @if($reply->parent_comment_id == $user_comment->id)
                <div class="comment-container comment reply mb-2">
                    <!-- Reply Content -->
                    <!-- ... -->
                    <div class="comment-content">
                        @if(strlen($reply->comments) > 150)
                        <p class="half-comment">{{ substr($reply->comments, 0, 150) }} <span class="read-more">... Read More</span></p>
                        <span class="full-comment" style="display: none;">{{ $reply->comments }}</span>
                        <p>
                            @if($reply->name != '')
                            <b>{{$reply->name}}</b>
                            @else
                            <b>Anonymous</b>
                            @endif
                        </p>
                        @else
                        <p>{{ $reply->comments }}</p>
                        <p>
                            @if($reply->name != '')
                            <b>{{$reply->name}}</b>
                            @else
                            <b>Anonymous</b>
                            @endif
                        </p>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif
            @endforeach
            @endif
            <form method="POST" action="{{url('add_user_comments_posts')}}">
                @csrf
                <input type="hidden" name="blog_id" value="{{$post->id}}">
                <div class="add_comments m-2 p-2">
                    <textarea name="comments" onpaste="return false" class="form-control comment-text" id="" cols="30" rows="10" placeholder="Add your comment here" maxlength="2000" required></textarea>
                    <small class="text-primary comment-warn">0/2000</small><br>
                    @auth
                    @else
                    @endauth
                </div>
                <div class="add_comment_button p-2 m-2">
                    <button type="submit" class="btn btn-primary float-end">Add</button>
                </div>
            </form>
            @endforeach

        </div>
        <div class="col-md-4">
            <div class="container">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>POPULAR CATEGORIES</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($popular_questions as $popular_question)
                            <li class="list-group-item"><a href="/category/{{str_replace(' ','-',$popular_question->topic_name)}}/{{str_replace(' ','-',$popular_question->question)}}">{{$popular_question->question}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>CONNECT & FOLLOW</h6>
                    </div>
                    <div class="card-body text-center">
                        <a id="" class="btn  m-2" href="https://www.facebook.com/people/Ifavecom/61553178323176/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a><a id="" class="btn m-2" href=""><i class="fa fa-linkedin-square" aria-hidden="true"></i></a><a href="" class="btn m-2"></a>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h6>NEWSLETTER</h6>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Enter your email address below to subscribe to our newsletter</p>
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
<script>
    $(document).ready(function() {
        // Add the img-fluid class to all <img> tags within the #content div
        $('#blog_content img').addClass('img-fluid');
        $('#blog_content span:has(img)').each(function() {
            // Remove the 'style' attribute from the <span> element
            $(this).removeAttr('style');
        });
        // $('#blog_content iframe').addClass('embed-responsive embed-responsive-4by3');
        let screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        if (screenWidth <= 768) {
            // deviceSize = 'mobile';
            $('#blog_content iframe').removeAttr('height');
            $('#blog_content iframe').removeAttr('width');
        }
        $('.comment-text').on('keyup', function() {
            $('.comment-warn').html($(this).val().length + '/2000 characters')
        })
    });

    function handleScreenWidthChange(mq) {
        if (mq.matches) {
            // Remove the class from the small element for mobile devices
            $(".col-md-4 small").removeClass("float-end");
        }
    }

    // Define the media query for mobile devices (screen width less than 768px)
    var mq = window.matchMedia("(max-width: 767px)");

    // Call the function initially to check the screen width
    handleScreenWidthChange(mq);

    // Add a listener for screen width changes
    mq.addListener(handleScreenWidthChange);
</script>