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
                    <p><b>Comments by all iFave users</b></p>
                    <ol>
                        @foreach($comments as $comment)
                        @if($comment->upvotes < 0) @php $comment->upvotes=0;
                            @endphp
                            @endif
                            <li><a rel="nofollow" class="link-secondary" href="/comments/{{ str_replace(' ', '-', $comment->name)}}">{{$comment->name}} ({{$comment->upvotes}} upvotes)</a></li>
                            @endforeach
                    </ol>
                    @if(count($comments) >= 5)
                    <div class="text-center top-comments-btn">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#top_comments_modal_for_comments" onclick="top_comments_modal_body_for_comments()">Show me more</button>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        @if(count($query) > 0)
        <div class="">
            @foreach($query as $user_comment)
            <div class="comment-container comment mb-2">
                <div class="comment-content">
                    <h5>Location: {{$user_comment->topic_name}} | Category: {{$user_comment->question}}</h5>
                    <hr>
                    @if(strlen($user_comment->comments) > 150)
                    <p class="half-comment">{{ substr($user_comment->comments, 0, 150) }} <span class="read-more">... Read More</span></p>
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

<!-- Modal -->
<div class="modal fade" id="top_comments_modal_for_comments" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <small>(Upvotes here are the grand total of all upvotes in all locations minus all downvotes.)</small>
                <div class="row height d-flex justify-content-center align-items-center mb-3">
                    <div class="col-md-8">
                        <div class="search">
                            <i class="fa fa-search"></i>
                            <input type="text" id="search_users_comments_on_comments_page" class="form-control" placeholder="Search">
                        </div>
                    </div>
                </div>
                <div id="top_comments_modal_body_for_comments"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('footer.footer')