@include('layouts.app')

<div class="container">
    <!-- <div class="text-center">
        <button class="btn btn-primary mb-5">Create Blog</button>
    </div> -->
    <!-- <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_questions" class="form-control" placeholder="Search for blogs">
                <div class="set_suggestion_height mt-3 d-none">

                </div>
            </div>
        </div>
    </div> -->
    @if(isset($topic_slug) && isset($question_slug))
    <input type="hidden" name="topic_slug" id="topic_slug" value="{{$topic_slug}}">
    <input type="hidden" name="question_slug" id="question_slug" value="{{$question_slug}}">
    @endif
    <div class="container">
        <h4>Filter</h4>
        <div class="row">
            <div class="col-md-5">
                <select class="custom-select form-control" id="location" name="topic_id" aria-label="Select Location">
                    <option selected disabled>Select Location</option>
                    @foreach($topics as $topic)
                    <option value="{{$topic->id}}-{{$topic->topic_name}}">{{$topic->topic_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select class="custom-select form-control" id="select_category" name="question_id" aria-label="Select Category">
                    <option selected disabled>Select Category</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary filter_blogs">Filter</button>
            </div>
        </div>
        <h4 class="mt-4">Bloggers</h4>
        <div class="container mb-5">
            <section class="regular slider d-none">
                @foreach($bloggers as $blogger)
                <div>
                    @if($blogger->image !=='' && $blogger->image !== null)
                    <img onclick="blogger_route('{{$blogger->username}}')" src="/images/user_images/{{$blogger->image}}" width="200px" height="200px">
                    @else
                    <img onclick="blogger_route('{{$blogger->username}}')" src="/images/user_images/IFAVE_PNG.png" width="200px" height="200px">
                    @endif
                    <p onclick="blogger_route('{{$blogger->username}}')" class="mt-3">{{$blogger->username}}</p>
                    <p onclick="blogger_route('{{$blogger->username}}')">Rating {{$blogger->rating}}</p>
                    <button onclick="blogger_bio('{{$blogger->bio}}','{{$blogger->username}}')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bio_modal" id="open_bio">bio</button>
                </div>
                @endforeach
            </section>
        </div>
    </div>
    <div class="container mt-4">
        @foreach($posts as $post)
        <div class="row border border-blue mt-3 p-2 m-2">
            <div class="col-md-3">
                <img src="/images/posts/{{$post->featured_image}}" class="img-fluid" height="300px" width="300px" alt="">
            </div>
            <div class="col-md-9">
                <h4 class="mt-2"><a href="/blog/{{$post->slug}}">{{$post->title}}</a></h4>
                {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
                <small><b>Date:</b> {{$post->created_at}}</small><br>
                <small><b>Author:</b> {{$post->name}}</small>
            </div>
        </div>
        @endforeach
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