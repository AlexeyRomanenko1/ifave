@include('layouts.app')
<div class="container">
    <div class="text-center">
        @foreach($header_info as $details)
        @php
        $topic_name=$details["topic_name"];
        $question_category=$details['question_category'];
        $question_id=$details['id'];
        $question=$details["question"];
        $question_to_share=str_replace(' ','-',$question);
        $topic_to_share=str_replace(" ", "-", $details["topic_name"]);
        $blog_question=str_replace(" ", "-", $details["question"]);
        $blog_topic_name=str_replace(" ", "-", $details["topic_name"]);
        $question_image=strtolower($details["question"]);
        $question_image=str_replace(" ","_",$question_image).".jpg";
        @endphp
        <input type="hidden" id="hidden_question_id" value="{{ $details['question_category'] }}">
        @if($details["topic_name"] == 'The World')
        <a href="/">Go back to best in {{ $details["topic_name"] }}</a>
        <h4 class="mt-2 p-2">{{ $details["topic_name"] }}</h4>
        @else
        <a href="/location/{{str_replace(' ','-',$details['topic_name'])}}">Go back to best in {{ $details["topic_name"] }}</a>
        <h4 class="mt-2 p-2">{{ $details["topic_name"] }}</h4>
        @endif
        @if (file_exists(public_path('images/question_images/ifave_images/'.$question_image)))
        <img src="/images/question_images/ifave_images/{{$question_image}}" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
        @else
        <img src="/images/question_images/ifave.jpg" class="img-fluid zoom-block" height="325px" width="325px" alt="...">
        @endif
        <h3 class="p-2">
            {{ $details["question"] }}
        </h3>
        @endforeach
        <input type="hidden" id="to_share_link" value="https://ifave.com/category/{{$topic_to_share}}/{{ $question_to_share }}">
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
                        <!-- Button trigger modal -->
                    </div>
                    <div class="text-nowrap bd-highlight m-2" style="width:7rem">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn p-0 info-large-screen" data-bs-toggle="modal" data-bs-target="#infographics">
                            <img src="/images/info-908889_1280.png" class="img-fluid zoom-block" alt="Info Graphics">
                        </button>
                        <a href="/generate-info-graphics/{{$topic_to_share}}/{{$question_to_share}}" class="info-small-screen d-none">
                            <img src="/images/info-908889_1280.png" class="img-fluid zoom-block" alt="Info Graphics">
                        </a>
                    </div>
                    <p class="">
                        <i class="fa fa-2x fa-clone float-center p-2 m-2" aria-hidden="true" onclick="copy_url('https://ifave.com/category/{{$topic_to_share}}/{{ $question_to_share }}')"></i> <i class="fa fa-2x fa-share float-center p-2 m-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url('https://ifave.com/category/{{$topic_to_share}}/{{ $question_to_share }}')"></i> <i class="fa fa-2x fa-code float-center p-2 m-2" aria-hidden="true" onclick="generate_embeded_code('https://ifave.com/category/{{$topic_to_share}}/{{ $question_to_share }}', '{{ $question }}')"></i>
                    </p>
                    @if($user_status==1 || $user_status==2)
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#thoughts_modal">Thoughts</button>
                    @endif
                </div>
                <div class="col-md-4">
                    <!-- <p class="p-1 mt-2"> <b>Click to fave</b></p> -->
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
    @if($thoughts !=='' || $thoughts !='' || $thoughts != NULL)
    <div class="container mb-4 mt-2">
        <div class="d-none hidden-cotnent">
            {!! $thoughts !!}
        </div>
        <div class="thoughts-content for-full-screen d-none">
            <div class="half-comment half-thoughts half-thoughts-full-screen"> </div><span class="read-more-thoughts">... Read More</span>
            <span class="full-comment" style="display: none;">{!! $thoughts !!}</span>
        </div>

        <div class="thoughts-content for-mobile-screen d-none">
            <div class="half-comment half-thoughts half-thoughts-mobile-screen"></div> <span class="read-more-thoughts">... Read More</span>
            <span class="full-comment" style="display: none;">{!! $thoughts !!}</span>
        </div>
    </div>
    @endif
    @if(count($posts) > 0)
    @if(count($posts) == 1)
    @php
    $col_md=12;
    @endphp
    @elseif(count($posts) > 1)
    @php
    $col_md=6;
    @endphp
    @endif
    <div class="container mb-4">
        <div class="row">
            @foreach($posts as $index=>$post)
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

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @if(count($posts) > 4)
    <div class="text-center"><a href="/blog">Show More Blogs</a></div>
    @endif
    @endif
    <div class="container">
        <div class="text-center">
            <h4>Comments</h4>
            <small><b>Please upvote detailed, well argumented and well presented comments and downvote irrelevant ones.</b></small>
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
                    <i class="fa fa-arrow-up upvote-icon" onclick="upvote_count({{$user_comment->id}},{{$user_comment->upvotes}})"></i>
                    <i class="fa fa-arrow-down downvote-icon" onclick="downvote_count({{$user_comment->id}},{{$user_comment->downvotes}})"></i>

                </div>
            </div>
            <a href="#" class="reply-btn" data-comment-id="{{ $user_comment->id }}">Reply</a>
            <form method="POST" action="{{ route('comments.storeReply') }}" class="reply-form reply-form-{{ $user_comment->id }} mt-3" style="display: none;">
                @csrf
                @foreach($header_info as $header)
                <input type="hidden" name="question_id" value="{{$header['id']}}">
                @endforeach
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
        <form id="add_comments">
            @csrf
            @foreach($header_info as $header)
            <input type="hidden" name="question_id" value="{{$header['id']}}">
            @endforeach
            <div class="add_comments m-2 p-2">
                <textarea name="comments" onpaste="return false" class="form-control comment-text" id="" cols="30" rows="10" placeholder="Add your comment here" maxlength="2000" required></textarea>
                <small class="text-primary comment-warn">0/2000</small><br>
                @auth
                @else
                <small><b>Login to participate in Best comments in this location or log out to comment anonymously.</b></small>
                @endauth
            </div>
            <div class="add_comment_button p-2 m-2">
                <button type="submit" class="btn btn-primary float-end">Add</button>
            </div>
        </form>

    </div>
    <div class="container mt-3">
        <div class="row">
            @foreach($all_posts as $all_post)
            <div class="col-md-3">
                <div class="p-3">
                    <div class="h-75">
                        <img src="/images/posts/{{$all_post->featured_image}}" class="zoom-block img-fluid" alt="">
                    </div>
                    <h4 class="mt-2"><a href="/blog/{{$all_post->slug}}">{{substr(strip_tags($all_post->title), 0, 100) }}</a></h4>
                    {!! substr(strip_tags($all_post->blog_content), 0, 150) !!}... <br><br>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination justify-content-center">
            {{ $all_posts->links('pagination::bootstrap-5') }}
        </div>
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
<!-- Modal -->
<div class="modal fade" id="thoughts_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thoughts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="submit_thoughts">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="question_id" value="{{$question_id}}">
                    <textarea class="form-control" name="thoughts" id="thoughts" rows="3" data-upload-url="{{ route('upload_content_image') }}">{!! $thoughts !!}</textarea>
                    <small id="content_error" class="text-danger d-none">This fied is required</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="sharecommentmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Share My Comment</h5>
                <button type="button" class="btn-close closesharecommentmodal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <p>Looks like you have left a thorough and detailed comment. Thanks you!</p>
                <div class="text-center">
                    <a id="facebook_share_comment" class="btn  m-2" href=""><i class="fa fa-facebook-square" aria-hidden="true"></i></a><a id="twitter_share_comment" class="btn m-2" href=""><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closesharecommentmodal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="infographics" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Infographics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="chart-container">
                    <h4 class="chart-title p-2" id="chart-heading">{{$question}} in {{$topic_name}}</h4>
                    <p class="ps-3"><small>as voted by iFave visitors</small></p>
                    <canvas id="myChart"></canvas>
                    <p class="text-center p-2 mt-3"><small>Visit ifave.com for more awesome rankings and infographics that you will enjoy!</small></p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="/generate-info-graphics/{{$topic_to_share}}/{{$question_to_share}}" class="btn btn-primary">
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="top_answers" value="{{$top_answers}}">
<input type="hidden" id="top_answers_votes" value="{{$top_answers_votes}}">
@include('footer.footer')
<script>
    let top_answer = $('#top_answers').val();
    let top_answer_votes = $('#top_answers_votes').val();
    let top_answers_data = top_answer.split('line_break');
    let top_votes_data = top_answer_votes.split(',');
    $('.modal-text').on('keyup', function() {
        $('.modal-warn').html($(this).val().length + '/2000 characters')
    })
    $('.comment-text').on('keyup', function() {
        $('.comment-warn').html($(this).val().length + '/2000 characters')
    })
    $('.user_fave').on('keyup', function() {

        $('.user-fave-warn').html($(this).val().length + '/50 characters')
    })
    const ctx = document.getElementById('myChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: top_answers_data,
            datasets: [{
                label: 'Histogram Data',
                data: top_votes_data,
                backgroundColor: 'blue', // Set the background color to white
                borderColor: 'blue', // Set the border color
                borderWidth: 1, // Add a border
            }],
        },
        options: {
            indexAxis: 'y', // Display bars along the y-axis for horizontal bars
            scales: {
                x: { // Use 'x' scale for horizontal bars
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    display: false, // Hide the legend
                },
            },
        },
    });

    // Customize the appearance of the chart
    chart.options.plugins.title = {
        display: true,
        text: '',
        font: {
            size: 16,
            weight: 'bold',
        },
    };

    chart.options.plugins.tooltips = {
        backgroundColor: 'white',
        titleColor: 'black',
        bodyColor: 'black',
    };

    // Add event listener to download the chart as an image in JPG format
    const downloadButton = document.getElementById('downloadButton');
    downloadButton.addEventListener('click', () => {
        const chartTitle = document.getElementById('chart-heading').innerText;
        chart.options.plugins.title.text = chartTitle; // Set the chart title

        const chartContainer = document.getElementById('chart-container');
        const canvas = document.getElementById('myChart');
        html2canvas(chartContainer, {
            scale: 2
        }).then(function(canvas) {
            chart.options.plugins.title.text = ''; // Reset the chart title
            const dataURL = canvas.toDataURL('image/jpeg', 1.0); // Set image format (JPEG) and quality
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'iFave-{{$topic_to_share}}-{{$question_category}}-infographic.jpg'; // Use the JPG file extension
            link.click();
        });
    });
</script>