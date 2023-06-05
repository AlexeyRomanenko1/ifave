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
                <input type="text" id="search_questions" class="form-control" placeholder="look for more questions within this topic">
                <!-- <button class="btn btn-primary">Search</button> -->
                <div class="set_suggestion_height mt-3 rounded">
                    @foreach($question_answers as $answers)
                    <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote({{ $answers['answer_id'] }})"><b>{{ $answers['answers'] }} (Votes: {{$answers['vote_count']}})</b></div>
                    @endforeach


                </div>
            </div>
            <div class="text-nowrap bd-highlight m-3" style="width:12rem">
                No idea show me answers.
            </div>
            <div class="text-nowrap bd-highlight m-2" style="width:14rem">
                Not in the list. Add an answer.
            </div>
        </div>
    </div>
    <div class="container">
        <div class="text-center">
            <h4>Comments</h4>
            <small><b>Please upvote detailed, well argumented and presented comments and downvote irrelevant onces.</b></small>
        </div>
        <div class="comment m-2 p-2">
            <div class="row">
                <div class="col-md-11">
                    <div class="border">
                        <p class="p-2">Forrest Gump is often overlooked because of many of the great movies that came out the same year such as the previously mentioned masterpiece Shawshank Redemption, another brilliant masterpiece Pulp Fiction, one of the best Disney films ever created in Lion King, one of the best Keanu Reeves movies in Speed, and more (also because it won the Best Picture which is fine with me this is all subjective). This does not take away from the fact that this is one of the best movies of the 1990s and likely all time.
                            Author: Michael Chan</p>
                    </div>
                </div>
                <div class="col-md-1 mt-5">
                    <small>12 Upvotes</small>
                    <div><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                    <div><i class="fa fa-arrow-down" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
        <div class="comment m-2 p-2">
            <div class="row">
                <div class="col-md-11">
                    <div class="border">
                        <p class="p-2">The Godfather. When I try to rank anything when it comes to sports, film, etc... I usually have personal bias as well as the opinions of others. Usually, something that holds the #1 spot in these types of lists are very popular, iconic, influential, and beloved. They are also widely accepted as the best like with Michael Jordan in basketball. I believe that The Godfather is the greatest movie ever. This movie was influential in so many ways, it changed the entire way people looked at how to make a movie. Also, there are so many iconic lines in that movie. There's a 90% chance you have heard someone say "I'm gonna make him an offer he can't refuse." Also, almost everyone ever has heard of this movie and most people love it whether they are the general public or movie critics. The movie itself it just amazing. This movie has one of the greatest directors (Coppola) directing it and possibly the greatest cast (Brando, Duvall, Pacino, etc) for a movie. I don't want to go too in depth with this argument.
                            Author: Jennifer Miller</p>
                    </div>
                </div>
                <div class="col-md-1 mt-5">
                    <small>9 Upvotes</small>
                    <div><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                    <div><i class="fa fa-arrow-down" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
        <div class="add_comments m-2 p-2">
            <textarea name="" class="form-control" id="" cols="30" rows="10" placeholder="Add your comment here"></textarea>
        </div>
        <div class="add_comment_button p-2 m-2">
            <button class="btn btn-primary float-end">Send</button>
        </div>
    </div>
</div>
@include('footer.footer')