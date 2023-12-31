@include('layouts.app')

<div class="container">
    <!-- <div class="text-center">
        <button class="btn btn-primary mb-5">Create Blog</button>
    </div> -->
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_blogs" class="form-control" placeholder="Search for blogs">
                <div class="set_suggestion_height mt-3 d-none">
                </div>
            </div>
        </div>
    </div>
    @if(isset($topic_slug) && isset($question_slug))
    <input type="hidden" name="topic_slug" id="topic_slug" value="{{$topic_slug}}">
    <input type="hidden" name="question_slug" id="question_slug" value="{{$question_slug}}">
    @endif
    <div class="container">
        <h4>Filter</h4>
        <div class="row">
            <div class="col-md-5 mb-3">
                <select class="select-2 form-control" id="location" name="topic_id" aria-label="Select Location">
                    @if(isset($topic_slug))
                    <option value="{{$topic_id}}-{{str_replace('-', ' ', $topic_slug)}}" selected>{{str_replace('-', " ", $topic_slug)}}</option>
                    @else
                    <option selected disabled>Select Location</option>
                    @endif

                </select>
            </div>
            <div class="col-md-5 mb-3">
                <div id="custom-select-category">
                    <select class="select-2 form-control" id="select_category" name="question_id" aria-label="Select Category">
                        @if(isset($categories))
                        <option value="{{str_replace('-', ' ', $question_slug)}}" selected>{{str_replace('-', " ", $question_slug)}}</option>
                        <option value="All Categories">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{$category->question}}">{{$category->question}}</option>
                        @endforeach
                        @else
                        <option selected disabled>Select Category</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary filter_blogs">Filter</button>
                @if(isset($topic_slug) || isset($question_slug) || isset($name))
                <a href="/blog" class="btn btn-success">Clear Filter</a>
                @endif
            </div>
        </div>
        <a ref="nofollow" class="link-primary mt-5" data-bs-toggle="modal" data-bs-target="#all_bloggers">
            <h4>All Bloggers</h4>
        </a>
        <div class="container mb-5">
            <section class="regular slider d-none">
                @foreach($bloggers as $blogger)
                <div class="p-2 m-2 text-center">
                    @if($blogger['image'] !=='' && $blogger['image'] !== null)
                    <img onclick="blogger_route('{{$blogger['username']}}')" src="/images/user_images/{{$blogger['image']}}" alt="{{$blogger['username']}}">
                    @else
                    <img onclick="blogger_route('{{$blogger['username']}}')" src="/images/user_images/default_profile_picture.jpg" alt="{{$blogger['username']}}">
                    @endif
                    <p onclick="blogger_route('{{$blogger['username']}}')" class="lh-1 m-0-p">{{$blogger['username']}}</p>
                    @if($blogger['location'] !='' || $blogger['location']!= null)
                    <p onclick="blogger_route('{{$blogger['username']}}')" class="lh-1 m-0-p"> {{$blogger['location']}}</p>
                    @else
                    <p onclick="blogger_route('{{$blogger['username']}}')" class="lh-1 m-0-p">Unknown</p>
                    @endif
                    <p onclick="blogger_route('{{$blogger['username']}}')" class="lh-1 m-0-p">Rating {{$blogger['rating']}}</p>
                    <button onclick="blogger_bio('{{$blogger['bio']}}','{{$blogger['username']}}')" class="btn btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#bio_modal" id="open_bio">bio</button>
                </div>
                @endforeach
            </section>
        </div>
    </div>
    <div class="container mt-4" id="posts_list">
        <div class="row">
            @foreach($posts as $index=>$post)
            <div class="col-md-3">
                <div class="p-3">
                    <div class="h-75">
                        @if($post->alt_text != '' || $post->alt_text != NULL)
                        <img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" alt="{{$post->alt_text}}">
                        @else
                        <img src="/images/posts/{{$post->featured_image}}" class="zoom-block img-fluid" alt="{{$post->title}}">
                        @endif
                    </div>
                    <h1 class="mt-4 ifave-h4"><a class="link-secondary" class="link-secondary" href="/blog/{{$post->slug}}">{{substr(strip_tags($post->title), 0, 100) }}</a></h1>
                    {!! substr(strip_tags($post->blog_content), 0, 150) !!}... <br><br>
                    @if(auth()->check())
                    @if( auth()->user()->id == $post->user_id)
                    <div class="mt-3">
                        <a ref="nofollow" href="/edit/blog/{{str_replace(' ','-',$post->name)}}/{{$post->slug}}/{{$post->id}}" class="btn btn-success">Edit Blog</a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            @if($index == 2)
            <div class="col-md-3">
                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h6>POPULAR CATEGORIES</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($popular_questions as $popular_question)
                            <li class="list-group-item"><a class="link-secondary" href="/category/{{str_replace(' ','-',$popular_question->topic_name)}}/{{str_replace(' ','-',$popular_question->question)}}">{{$popular_question->question}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="pagination justify-content-center">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="bio_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title blogger-bio-title" id="exampleModalLabel">Bio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container" id="blogger_bio_content">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('footer.footer')