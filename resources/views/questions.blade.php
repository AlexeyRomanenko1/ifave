@include('layouts.app')
<div class="container">
    <div class="text-center">


        @foreach($header_info as $details)
        <input type="hidden" id="hidden_question_id" value="{{ $details['question_category'] }}">
        <a href="/">Go back to {{ $details["topic_name"] }}</a>
        <h3 class="p-2">
            {{ $details["question"] }}
        </h3>
        @endforeach
    </div>
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_question_topics" class="form-control" placeholder="look for more questions within this topic">
                <!-- <button class="btn btn-primary">Search</button> -->
                <div class="set_suggestion_height mt-3 rounded">
                    @if(count($get_user_answers) > 0)
                    <input type="hidden" id="hidden_to_be" value="0">
                    @foreach($question_answers as $answers)
                    <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote({{ $answers['answer_id'] }})"><b>{{ $answers['answers'] }} (Votes: {{$answers['vote_count']}})</b></div>
                    @endforeach
                    @else
                    @foreach($question_answers as $answers)
                    <input type="hidden" id="hidden_to_be" value="1">
                    <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote({{ $answers['answer_id'] }})"><b>{{ $answers['answers'] }}</b></div>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="text-nowrap bd-highlight m-3" style="width:12rem">
                        <button class="btn btn-primary">No idea show me answers.</button>
                    </div>
                    <div class="text-nowrap bd-highlight m-2" style="width:14rem">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Not in the list. Add an answer.</button>
                    </div>
                </div>
                <div class="col-md-4">
                    @if(count($get_user_answers) > 0)
                    <table class="table">
                        <thead>
                            <th>My Votes</th>
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
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="text-center">
            <h4>Comments</h4>
            <small><b>Please upvote detailed, well argumented and presented comments and downvote irrelevant onces.</b></small>
        </div>
        @if(count($get_comments) > 0)
        @foreach($get_comments as $user_comment)
        <div class="comment m-2 p-2">
            <div class="row">
                <div class="col-md-11">
                    <div class="border">
                        <p class="p-2">{{ $user_comment->comments }}</p>
                    </div>
                </div>
                <div class="col-md-1">
                    <small>{{$user_comment->upvotes}} Upvotes</small>
                    <div><i class="fa fa-arrow-up" onclick="upvote_count({{$user_comment->id}},{{$user_comment->upvotes}})" aria-hidden="true"></i></div>
                    <div><i class="fa fa-arrow-down" onclick="downvote_count({{$user_comment->id}},{{$user_comment->downvotes}})" aria-hidden="true"></i></div>
                    <small>{{$user_comment->downvotes}} Downvotes</small>
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
                <textarea name="comments" class="form-control" id="" cols="30" rows="10" placeholder="Add your comment here"></textarea>
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
                <h5 class="modal-title" id="exampleModalLabel">Add answer for {{$header["question"]}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <small class="p-2">Note: <ul>
                    <li>Please make sure that the answer you are adding is not already in the list.(Try different spelling).</li>
                    <li>You can add up to 3 answers per question</li>
                    <li>Please ensure that your spelling is correct or other visitors will not be able to find and upvote your entry. Do not add contact info</li>
                    <li>The answer will be added to the list with 1 vote.</li>
                </ul></small>
            <form method="POST" action="{{url('add_user_answer')}}" class="form-control">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="category" value="{{$header['question_category']}}">
                    <input type="text" class="form-control m-1" name="add_answer[]" id="" placeholder="Add your answer here" required>
                    <input type="text" class="form-control m-1" name="add_answer[]" id="" placeholder="Add your answer here">
                    <input type="text" class="form-control m-1" name="add_answer[]" id="" placeholder="Add your answer here">
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
                    <textarea class="form-control" name="comments" id="" cols="30" rows="10" placeholder="Your comments....." required></textarea>
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