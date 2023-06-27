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