@include('layouts.app')


<div class="container min-vh-100">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="text-center">
                    <h4>Comments by {{$user_name}}</h4>
                </div>
            </div>
            @if(count($comments) > 0)
            <div class="col-md-4">
                <div class="container border border-blue mt-3 p-2 m-2">
                    <p><b>Comments by other users</b></p>
                    <ol>
                        @foreach($comments as $comment)
                        <li><a href="/comments/{{ str_replace(' ', '-', $comment->name)}}">{{$comment->name}} ({{$comment->upvotes}} upvotes)</a></li>
                        @endforeach
                    </ol>
                    @if(count($comments) >= 5)
                    <div class="text-center top-comments-btn">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#top_comments_modal" onclick="top_comments_modal({{$topic_id}})">Show me more</button>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        @if(count($query) > 0)
        <div class="comment-container">
            @foreach($query as $user_comment)
            <div class="comment">
                <div class="comment-content">
                    <h5>Category: {{$user_comment->question}}</h5>
                    <hr>
                    @if(strlen($user_comment->comments) > 150)
                    <p>{{ substr($user_comment->comments, 0, 150) }} <span class="read-more">... Read More</span></p>
                    <span class="full-comment" style="display: none;">{{ $user_comment->comments }}</span>
                    @else
                    <p>{{ $user_comment->comments }}</p>
                    @endif
                </div>
                <div class="comment-actions">
                    <small>{{$user_comment->upvotes}} Upvotes</small>
                    <div>
                        <i class="fa fa-arrow-up upvote-icon" onclick="upvote_count({{$user_comment->id}},{{$user_comment->upvotes}})"></i>
                        <i class="fa fa-arrow-down downvote-icon" onclick="downvote_count({{$user_comment->id}},{{$user_comment->downvotes}})"></i>
                        
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@include('footer.footer')