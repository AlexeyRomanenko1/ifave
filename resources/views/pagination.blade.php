<div class="row mt-5" id="display_questions">
    @php

    $jsonString = $subQuery;
    $array = json_decode($jsonString, true);
    @endphp
    @foreach ($questions as $main_loop=>$question)
    <!-- Display question information -->
    @php
    $answers=explode("}",$question->top_answers);
    $question_id=$question->question_id;
    //$exists = array_reduce(array_column($array, 'question_id'), function ($carry, $item) use ($question_id) {
    //return $carry || $item == $question_id;
    //}, false);
    $exists =true;
    $question_image=strtolower($question->question);
    $question_image=str_replace(" ","_",$question_image).".jpg";
    @endphp
    @if($main_loop==2)
    @if(count($comments) > 0)
    <div class="col-md-4">
        <div class="container border border-blue mt-3 p-2 m-2">
            <p><b>Best comments in this location</b></p>
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
    @endif
    @if($main_loop == 5)
    @php
    $post_loop=1;
    @endphp
    @if(count($posts) == 1)
    @php
    $col_md=12;
    @endphp
    @elseif(count($posts) > 1)
    @php
    $col_md=6;
    @endphp
    @endif
    @foreach($posts as $post)
    @if($post_loop < 3) <div class="col-md-{{$col_md}}">
        <div class="container mt-3 p-2 m-2">
            <div class="row">
                <div class="col-md-3">
                    <img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" alt="">
                </div>
                <div class="col-md-9">
                    <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{ substr(strip_tags($post->title), 0, 100)}}</a></h4>
                    @if($col_md==6)
                    {!! substr(strip_tags($post->blog_content), 0, 150) !!}... <br><br>
                    @elseif($col_md==12)
                    {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
                    @endif
                    <small><b>Date:</b>{{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
                    <small><b>Author:</b> {{$post->name}}</small>
                </div>
            </div>
        </div>
        @php
        $post_loop=$post_loop+1;
        @endphp
</div>
@endif
@endforeach
@endif
@if($main_loop == 14)
@if(count($posts) == 3)
@php
$col_md=12;
@endphp
@elseif(count($posts) > 3)
@php
$col_md=6;
@endphp
@endif
@foreach($posts as $index=>$post)
@if($index > 1)
<div class="col-md-{{$col_md}}">
    <div class="container mt-3 p-2 m-2">
        <div class="row">
            <div class="col-md-3">
                <img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" alt="">
            </div>
            <div class="col-md-9">
                <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{ substr(strip_tags($post->title), 0, 100)}}</a></h4>
                @if($col_md==6)
                {!! substr(strip_tags($post->blog_content), 0, 150) !!}... <br><br>
                @elseif($col_md==12)
                {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
                @endif
                <small><b>Date:</b> {{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
                <small><b>Author:</b> {{$post->name}}</small>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endif
<div class="col-md-4 mb-4">
    <div class="p-3 border border-blue mt-3">
        <div class="question">
            <div class="h-fixed-30 border-bottom">
                @if (strlen($question->question)> 40)
                @php
                $question->question=substr($question->question, 0, 40) . '...';
                @endphp
                @endif
                <div class="text-center" onclick="redirect_url({{$question->question_id}})">
                    <h3 class="underline">{{ $question->question }} <small class="fs-6 fw-normal fst-italic"> ({{ $question->total_votes }} faves)</small></h3>
                </div>
            </div>
            <div class="suggestions p-1"></div>
            <div class="text-center">
                @if (file_exists(public_path('images/question_images/ifave_images/'.$question_image)))
                <img onclick="redirect_url({{$question->question_id}})" src="/images/question_images/ifave_images/{{$question_image}}" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
                @else
                <img onclick="redirect_url({{$question->question_id}})" src="/images/question_images/ifave.jpg" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
                @endif
            </div>
            @if(!$exists)
            @php
            $lm=1;
            @endphp
            @foreach ($answers as &$answer)
            @php
            $answer_votes = substr($answer, strpos($answer, "( Faves") + 1);
            @endphp
            @if($lm==1)
            <div class="p-1 mt-4">
                <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> Place ( {{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/questions_details/{{$question->question_id}}' )" aria-hidden="true"></i>
            </div>
            @elseif($lm==2)
            <div class="p-1">
                <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> Place ( {{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/questions_details/{{$question->question_id}}' )" aria-hidden="true"></i>
            </div>
            @elseif($lm==3)
            <div class="p-1">
                <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> Place ( {{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/questions_details/{{$question->question_id}}','{{$question->question}}')" aria-hidden="true"></i>
            </div>
            @endif
            @php
            $lm=$lm+1;
            @endphp
            @endforeach
            @else
            @if(count($answers) > 1)
            @for($m=0; $m < count($answers);$m++) @php preg_match('/^(.*)(\(Faves: \d+\))$/', $answers[$m], $matches); $text=$matches[1]; $faves=$matches[2]; if (strlen($text)> 18) {
                $text = substr($text, 0, 18) . '...';
                }
                $to_answer = $text . $faves;
                @endphp
                @if($m==0)
                <div class="p-1 mt-4"> <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/questions_details/{{$question->question_id}}')" aria-hidden="true"></i>
                </div>
                @elseif($m==1)
                <div class="p-1"> <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/questions_details/{{$question->question_id}}')" aria-hidden="true"></i>
                </div>
                @elseif($m==2)
                <div class="p-1"> <small onclick="redirect_url({{$question->question_id}})" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/questions_details/{{$question->question_id}}','{{$question->question}}')" aria-hidden="true"></i>
                </div>
                @endif
                @endfor
                @endif
                @endif
                <!-- <div class="text-center"><a href="/questions_details/{{$question->question_id}}" class="btn btn-primary m-2">Show me more</a></div> -->
        </div>
    </div>
</div>
@php

@endphp
@endforeach
<!-- Container for pagination links -->
<div id="pagination_links" class="pagination justify-content-center">
    {{ $questions->links('pagination::bootstrap-5') }}
</div>
</div>