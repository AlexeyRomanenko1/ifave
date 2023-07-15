@include('layouts.app')
<div class="container">
    <div class="text-center">
        @foreach($header_info as $details)
        @php
        $question_category=$details['question_category'];
        $question_id=$details['id'];
        $question=$details["question"];
        $blog_question=str_replace(" ", "-", $details["question"]);
        $blog_topic_name=str_replace(" ", "-", $details["topic_name"]);

        @endphp
        <input type="hidden" id="hidden_question_id" value="{{ $details['question_category'] }}">
        @if($details["topic_name"] == 'movies')
        <a href="/">Go back to best in {{ $details["topic_name"] }}</a>
        <h4 class="mt-2 p-2">{{ $details["topic_name"] }}</h4>
        @else
        <a href="/topics/{{$details['topic_name']}}">Go back to best in {{ $details["topic_name"] }}</a>
        <h4 class="mt-2 p-2">{{ $details["topic_name"] }}</h4>
        @endif
        <h3 class="p-2">
            {{ $details["question"] }}
        </h3>
        @endforeach
    </div>
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_question_topics" class="form-control" placeholder="Search">
                <!-- <button class="btn btn-primary">Search</button> -->
                <div class="set_suggestion_height mt-3 rounded">
                    @if(count($get_user_answers) > 0)
                    <input type="hidden" id="hidden_to_be" value="0">
                    @php
                    $question_answers = $question_answers->sortByDesc('vote_count');
                    $places=1;
                    @endphp
                    @foreach($question_answers as $answers)
                    <div class="hover p-2 bg-light unselect" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote({{ $answers['answer_id'] }})"><b>{{ $places }}. {{ $answers['answers'] }} (faves: {{$answers['vote_count']}})</b></div>
                    @php
                    $places=$places+1;
                    @endphp
                    @endforeach
                    @else
                    @foreach($question_answers as $answers)
                    <input type="hidden" id="hidden_to_be" value="1">
                    <div class="hover p-2 bg-light unselect" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote({{ $answers['answer_id'] }})"><b>{{ $answers['answers'] }}</b></div>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="m-3" style="width:12rem">
                        <button class="btn btn-grey" onclick="un_cover('{{$question_category}}')">No idea. Show me</button>
                    </div>
                    <div class="text-nowrap bd-highlight m-2" style="width:14rem">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Not in the list. Add my fave</button>
                    </div>
                    <div class="text-nowrap bd-highlight m-2" style="width:14rem">
                        <a href="/create-blog/{{$blog_topic_name}}/{{$blog_question}}" class="btn btn-primary">Create blog on this category</a>
                    </div>
                    <p class="">
                        <i class="fa fa-2x fa-clone float-center p-2 m-2" aria-hidden="true" onclick="copy_url('https://ifave.com/questions_details/'+{{ $question_id }})"></i> <i class="fa fa-2x fa-share float-center p-2 m-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/questions_details/' + {{$question_id}})"></i> <i class="fa fa-2x fa-code float-center p-2 m-2" aria-hidden="true" onclick="generate_embeded_code('https://ifave.com/questions_details/' +{{ $question_id }}, '{{ $question }}')"></i>
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="p-1 mt-2"> <b>Click to fave</b></p>
                    @if(count($get_user_answers) > 0)
                    <table class="table">
                        <thead>
                            <th>My Faves</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach($get_user_answers as $user_answer_list)
                            <tr>
                                <td>{{$user_answer_list['answers']}}</td>
                                <td><i class="fa fa-times text-center" onclick="delete_answer({{$user_answer_list['id']}},{{$user_answer_list['answer_id']}})" aria-hidden="true"></i></td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <table class="table">
                            <thead>
                                <th>My Faves</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>No faves yet</td>
                                </tr>
                            </tbody>
                            @endif
                        </table>
                </div>
            </div>
        </div>
    </div>
    @if(count($posts) > 0)
    <div class="container mb-4">
        <div class="row">
            @foreach($posts as $post)
            <div class="col-md-2"><img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" height="300px" width="300px" alt=""></div>
            <div class="col-md-10 pt-5">
                <h4><a href="">{{$post->title}}</a></h4>
                {!! substr(strip_tags($post->blog_content), 0, 500) !!}...
                &nbsp;<a href=""> read more</a>
            </div>
            @endforeach
        </div>
    </div>
    @if(count($posts) > 4)
    <div class="text-center"><a href="">Show More Blogs</a></div>
    @endif
    @endif
    <div class="container">
        <div class="text-center">
            <h4>Comments</h4>
            <small><b>Please upvote detailed, well argumented and well presented comments and downvote irrelevant ones.</b></small>
        </div>
        @if(count($get_comments) > 0)
        @foreach($get_comments as $user_comment)
        <div class="comment m-2 p-2">
            <div class="row">
                <div class="col-md-11">
                    <div class="border">
                        @if(strlen($user_comment->comments) > 150)
                        <p class="p-2">
                            {{ substr($user_comment->comments, 0, 150) }}
                            <a href="#" class="read-more">Read More</a>
                            <span class="full-comment" style="display: none;">{{ $user_comment->comments }}</span>
                        </p>
                        @else
                        <p class="p-2">{{ $user_comment->comments }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-1">
                    <small>{{$user_comment->upvotes}} Upvotes</small>
                    <div><i class="fa fa-arrow-up" onclick="upvote_count({{$user_comment->id}},{{$user_comment->upvotes}})" aria-hidden="true"></i></div>
                    <div><i class="fa fa-arrow-down" onclick="downvote_count({{$user_comment->id}},{{$user_comment->downvotes}})" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <!-- <div class="comment m-2 p-2">
            <div class="row">
                <div class="col-md-11">
                    <div class="border">
                        <p class="p-2">The Godfather. When I try to rank anything when it comes to sports, film, etc... I usually have personal bias as well as the opinions of others. Usually, something that holds the #1 spot in these types of lists are very popular, iconic, influential, and beloved. They are also widely accepted as the best like with Michael Jordan in basketball. I believe that The Godfather is the greatest movie ever. This movie was influential in so many ways, it changed the entire way people looked at how to make a movie. Also, there are so many iconic lines in that movie. There's a 90% chance you have heard someone say "I'm gonna make him an offer he can't refuse." Also, almost everyone ever has heard of this movie and most people love it whether they are the general public or movie critics. The movie itself it just amazing. This movie has one of the greatest directors (Coppola) directing it and possibly the greatest cast (Brando, Duvall, Pacino, etc) for a movie. I don't want to go too in depth with this argument.
                            Author: Jennifer Miller</p>
                    </div>
                </div>
                <div class="col-md-1 mt-1">
                    <small>9 Upvotes</small>
                    <div><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                    <div><i class="fa fa-arrow-down" aria-hidden="true"></i></div>
                    <small>3 Downvotes</small>
                </div>
            </div>
        </div> -->
        <form method="POST" action="{{url('add_user_comments')}}">
            @csrf
            @foreach($header_info as $header)
            <input type="hidden" name="question_id" value="{{$header['id']}}">
            @endforeach
            <div class="add_comments m-2 p-2">
                <textarea name="comments" onpaste="return false" class="form-control comment-text" id="" cols="30" rows="10" placeholder="Add your comment here" maxlength="2000" required></textarea>
                <small class="text-primary comment-warn">0/2000</small>
            </div>
            <div class="add_comment_button p-2 m-2">
                <button type="submit" class="btn btn-primary float-end">Add</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @foreach($header_info as $header)
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add your fave for {{$header["question"]}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <small class="p-2">Note: <ul>
                    <li>Please make sure that the entry you are adding is not already in the list with a different spelling. Double entry is a lost entry</li>
                    <li>You can add up to 3 entries per category in one day </li>
                    <li>Please ensure that your spelling is correct or other visitors will not be able to find and upvote your entry. Do not add contact info</li>
                    <li>The fave will be added to the list with 1 vote</li>
                </ul></small>
            <form method="POST" action="{{url('add_user_answer')}}" class="form-control">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="category" value="{{$header['question_category']}}">
                    <input type="text" class="form-control m-1 user_fave" onpaste="return false" name="add_answer[]" id="" maxlength="50" placeholder="Add your fave here" required>
                    <small class="text-primary user-fave-warn text-center">0/50</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Answer</button>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @foreach($header_info as $header)
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add comments for {{$header["question"]}}</h5>
                <button type="button" class="btn-close skip" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <h6 class="text-center">Please let us know what you voted for and why</h6>
            <form method="POST" action="{{url('add_user_comments')}}" class="form-control">
                @csrf
                <input type="hidden" name="question_id" value="{{$header['id']}}">
                <div class="modal-body">
                    <textarea class="form-control modal-text" onpaste="return false" name="comments" id="" cols="30" rows="10" placeholder="Your comments....." maxlength="2000" required></textarea>
                    <small class="text-primary modal-warn text-center">0/2000</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary skip" data-bs-dismiss="modal">Skip</button>
                    <button type="submit" class="btn btn-primary">Add Comments</button>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</div>
@include('footer.footer')
<script>
    $('.modal-text').on('keyup', function() {
        $('.modal-warn').html($(this).val().length + '/2000 characters')
    })
    $('.comment-text').on('keyup', function() {
        $('.comment-warn').html($(this).val().length + '/2000 characters')
    })
    $('.user_fave').on('keyup', function() {

        $('.user-fave-warn').html($(this).val().length + '/50 characters')
    })
</script>