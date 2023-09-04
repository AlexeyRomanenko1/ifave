@include('layouts.app')
<input type="hidden" value="1" name="topic_id" id="topic_id" value="{{$topic_id}}">
<input type="hidden" value="{{str_replace(' ','-',$topicName)}}" name="topicName" id="topicName">
<div class="container mt-5">
    <div class="text-center">
        <a href="" data-bs-toggle="modal" data-bs-target="#topics_modal">
            <h3 class="mb-3"> Select location</h3>
        </a>
        @if(count($get_last_three_locations) > 0)
        <div class="mb-3">
        @foreach($get_last_three_locations as $recent_links)
        <a class="mt-2 mb-2" href="/location/{{$recent_links->location_link}}">{{$recent_links->location}}</a>&nbsp;
        @endforeach
        </div>
        @endif
        <div class="container position-relative fav_tracks_parent">
            <div class="position-absolute fav_tracks">
                <div class="container">
                    <table class="table table-bordered border-blue user_faves_track">
                        <thead>
                            <td>
                                <p class="fs-6"><b>My faves</b></p>
                            </td>
                            @auth
                            <td>
                                <p><a href="" class="fs-6" data-bs-toggle="modal" data-bs-target="#myfavetrack">All my faves</a></p>
                            </td>
                            @else
                            <td>
                                <p class="fs-6">Login to keep track of your faves</p>
                            </td>
                            @endauth
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <h3 class="mb-3" id="display_topic_name"></h3>
    </div>
    <!-- <div class="container position-relative mb-4 mt-5">
        <i onclick="scrollRight()" class="fa fa-4x fa-angle-double-right position-absolute right-scroll-btn" aria-hidden="true"></i>
        <i onclick="scrollLeftcont()" class="fa fa-4x fa-angle-double-left position-absolute left-scroll-btn" aria-hidden="true"></i>
        <div class="container fixed-width d-flex " id="scrollContainer">

        </div>
    </div> -->
    <div class="container text-center mb-5">
        <h3><a a href="" data-bs-toggle="modal" id="open_search_category_modal" data-bs-target="#all_categories">All categories</a></h3>
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
            <!-- 200/160 image size -->
            <div class="row mt-5" id="display_questions">
                @php

                $jsonString = $subQuery;
                $array = json_decode($jsonString, true);
                @endphp
                @foreach ($questions as $main_loop=>$question)
                <!-- Display question information -->
                @php
                $TopicName=str_replace(' ','-',$topicName);
                $questionName=str_replace(' ','-',$question->question);
                $answers=explode("}",$question->top_answers);
                $question_id=$question->question_id;
                // $exists = array_reduce(array_column($array, 'question_id'), function ($carry, $item) use ($question_id) {
                // return $carry || $item == $question_id;
                // }, false);
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
                            @if($comment->upvotes < 0) @php $comment->upvotes=0;
                                @endphp
                                @endif
                                <li><a href="/comments/{{ str_replace(' ', '-', $comment->name)}}">{{$comment->name}} ({{$comment->upvotes}} upvotes)</a></li>
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
                                <small> {{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
                                <small> {{$post->name}}</small>
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
                            <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{substr(strip_tags($post->title), 0, 100) }}</a></h4>
                            @if($col_md==6)
                            {!! substr(strip_tags($post->blog_content), 0, 150) !!}... <br><br>
                            @elseif($col_md==12)
                            {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
                            @endif
                            <small> {{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
                            <small> {{$post->name}}</small>
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
                            <div class="text-center" onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')">
                                <h3 class="underline">{{ $question->question }} <small class="fs-6 fw-normal fst-italic"> ({{ $question->total_votes }} faves)</small></h3>
                            </div>
                        </div>
                        <div class="suggestions p-1"></div>
                        <div class="text-center">
                            @if (file_exists(public_path('images/question_images/ifave_images/'.$question_image)))
                            <img onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" src="/images/question_images/ifave_images/{{$question_image}}" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
                            @else
                            <img onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" src="/images/question_images/ifave.jpg" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
                            @endif
                        </div>
                        @if(!$exists)
                        @php
                        $lm=1;
                        @endphp
                        @foreach ($answers as &$answer)
                        @php
                        // $answer_votes = substr($answer, strpos($answer, "( Faves") + 1);

                        @endphp
                        @if($lm==1)
                        <div class="p-1 mt-4">
                            <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> Place ({{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/category/{{$TopicName}}/{{$questionName}}' )" aria-hidden="true"></i>
                        </div>
                        @elseif($lm==2)
                        <div class="p-1">
                            <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> Place ({{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/category/{{$TopicName}}/{{$questionName}}')" aria-hidden="true"></i>
                        </div>
                        @elseif($lm==3)
                        <div class="p-1">
                            <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> Place ({{$answer_votes}} </small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/category/{{$TopicName}}/{{$questionName}}','{{$question->question}}')" aria-hidden="true"></i>
                        </div>
                        @endif
                        @php
                        $lm=$lm+1;
                        @endphp
                        @endforeach
                        @else
                        @for($m=0; $m < count($answers);$m++) @php preg_match('/^(.*)(\(Faves: \d+\))$/', $answers[$m], $matches); $text=$matches[1]; $faves=$matches[2]; if (strlen($text)> 18) {
                            $text = substr($text, 0, 18) . '...';
                            }
                            $to_answer = $text . $faves;
                            //$TopicName=str_replace(' ','-',$topicName);
                            @endphp
                            @if($m==0)
                            <div class="p-1 mt-4"> <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url('https://ifave.com/category/{{$TopicName}}/{{$questionName}}')" aria-hidden="true"></i>
                            </div>
                            @elseif($m==1)
                            <div class="p-1"> <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/category/{{$TopicName}}/{{$questionName}}')" aria-hidden="true"></i>
                            </div>
                            @elseif($m==2)
                            <div class="p-1"> <small onclick="redirect_url('category/{{$TopicName}}/{{$questionName}}')" class="fw-normal fs-6  unselect underline"> {{$to_answer}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" onclick="generate_embeded_code('https://ifave.com/category/{{$TopicName}}/{{$questionName}}','{{$question->question}}')" aria-hidden="true"></i>
                            </div>
                            @endif
                            @endfor
                            @endif
                            <!-- <div class="text-center"><a href="/questions_details/{{$question->question_id}}" class="btn btn-primary m-2">Show me more</a></div> -->
                    </div>
                </div>
            </div>
            @php

            @endphp
            @endforeach
            <div class="pagination justify-content-center">
                {{ $questions->links('pagination::bootstrap-5') }}
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
<!-- Modal -->
<div class="modal fade" id="all_categories" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row height d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                        <div class="search">
                            <i class="fa fa-search"></i>
                            <input type="text" id="search_categories" class="form-control" placeholder="Search for category">
                            <div class="set_suggestion_height mt-3 d-none">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="set_suggestion_height_categories mt-3 rounded container">
                    <div class="row" id="on_search_category">


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('footer.footer')