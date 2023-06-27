@include('layouts.app')
<input type="hidden" value="1" name="topic_id" id="topic_id">
<div class="container mt-5">
    <div class="text-center">
        <a href="" data-bs-toggle="modal" data-bs-target="#topics_modal">
            <h3 class="mb-3">Select your location</h3>
        </a>
        <div class="container position-relative fav_tracks_parent">
            <div class="position-absolute fav_tracks">
                <div class="container">
                    <table class="table table-bordered border-blue">
                        <thead>
                            <th>My faves</th>
                            @auth
                            <td><a href="" data-bs-toggle="modal" data-bs-target="#myfavetrack">All my faves</a></td>
                            @else
                            <td>Register to keep track of your faves</td>
                            @endauth
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <h3 class="mb-3" id="display_topic_name"></h3>
    </div>
    <div class="container position-relative mb-4 mt-5">
        <i onclick="scrollRight()" class="fa fa-4x fa-angle-double-right position-absolute right-scroll-btn" aria-hidden="true"></i>
        <i onclick="scrollLeftcont()" class="fa fa-4x fa-angle-double-left position-absolute left-scroll-btn" aria-hidden="true"></i>
        <div class="container fixed-width d-flex " id="scrollContainer">

        </div>
    </div>
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_questions" class="form-control" placeholder="Search for category">
                <div class="set_suggestion_height mt-3 d-none">

                </div>
            </div>
        </div>

    </div>
    <div class="container">
        <div class="">
            <div class="row mt-5" id="display_questions">
                @php
                $main_loop=1;
                $jsonString = $subQuery;
                $array = json_decode($jsonString, true);
                @endphp
                @foreach ($questions as $question)
                <!-- Display question information -->
                @php
                $answers=explode("}",$question->top_answers);
                $question_id=$question->question_id;
                $exists = array_reduce(array_column($array, 'question_id'), function ($carry, $item) use ($question_id) {
                return $carry || $item == $question_id;
                }, false);

                @endphp
                @if($main_loop==3)
                <div class="col-md-4">
                    <div class="container border border-blue mt-1 p-2 m-2">
                        <p><b>Best comments in this topic</b></p>
                        <ol>
                            @foreach($comments as $comment)
                            <li>{{$comment->name}} ({{$comment->upvotes}} upvotes)</li>
                            @endforeach
                        </ol>
                        @if(count($comments) >=5)
                        <div class="text-center"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#top_comments_modal" onclick="top_comments_modal({{$topic_id}})">Show me more</button></div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="col-md-4 mb-4">
                    <div class="container border border-blue mt-1">
                        <div class="question">
                            <div class="h-fixed-30 border-bottom">
                                <h5 class="p-3 ">{{ $question->question }} ({{ $question->total_votes }} Faves)</h5>
                            </div>
                            <div class="suggestions p-1"></div>
                            @if(!$exists)
                            @php
                            $lm=1;
                            @endphp
                            @foreach ($answers as &$answer)
                            @php
                            $answer_votes = substr($answer, strpos($answer, "( Faves") + 1);
                            @endphp
                            @if($lm==1)
                            <div class="hover p-1">
                                Place ( {{$answer_votes}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/questions_details/{{$question->question_id}}' )" aria-hidden="true"></i>
                            </div>
                            @elseif($lm==2)
                            <div class="hover p-1">
                                Place ( {{$answer_votes}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/questions_details/{{$question->question_id}}' )" aria-hidden="true"></i>
                            </div>
                            @elseif($lm==3)
                            <div class="hover p-1">
                                Place ( {{$answer_votes}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/questions_details/{{$question->question_id}}','{{$question->question}}')" aria-hidden="true"></i>
                            </div>
                            @endif
                            @php
                            $lm=$lm+1;
                            @endphp
                            @endforeach
                            @else
                            @for($m=0; $m < count($answers);$m++) @php preg_match('/^(.*)(\( Faves: \d+\))$/', $answers[$m], $matches); $text=$matches[1]; $faves=$matches[2]; if (strlen($text)> 18) {
                                $text = substr($text, 0, 18) . '...';
                                }
                                $to_answer = $text . $faves;
                                @endphp
                                @if($m==0)
                                <div class="hover p-1">{{$to_answer}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/questions_details/{{$question->question_id}}')" aria-hidden="true"></i>
                                </div>
                                @elseif($m==1)
                                <div class="hover p-1">{{$to_answer}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/questions_details/{{$question->question_id}}')" aria-hidden="true"></i>
                                </div>
                                @elseif($m==2)
                                <div class="hover p-1">{{$to_answer}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/questions_details/{{$question->question_id}}','{{$question->question}}')" aria-hidden="true"></i>
                                </div>
                                @endif
                                @endfor
                                @endif
                                <div class="text-center"><a href="/questions_details/{{$question->question_id}}" class="btn btn-primary m-2">Show me more</a></div>
                        </div>
                    </div>
                </div>
                @php
                $main_loop=$main_loop+1;
                @endphp
                @endforeach
                <div class="pagination justify-content-center">
                    {{ $questions->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <div class="text-center" id="pagination">
                <!-- Pagination controls will be added dynamically -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title question_modal_heading" id="exampleModalLabel">Best movie ever (421 votes)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container border mt-1">
                    <!-- <h6 class="p-3 border-bottom">Q: Best Comedy (289 votes)</h6> -->
                    <div class="question">
                        <input type="text" class="form-control mb-1 questions_answer_search" placeholder="Search options">
                        <div class="modal-suggestions">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

@include('footer.footer')