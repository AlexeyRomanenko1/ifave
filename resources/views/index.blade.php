@include('layouts.app')
<input type="hidden" value="1" name="topic_id" id="topic_id" value="{{$topic_id}}">
<input type="hidden" value="{{str_replace(' ','-',$topicName)}}" name="topicName" id="topicName">
<div class="container mt-5">
    <div class="text-center">
        <a rel="nofollow" data-bs-toggle="modal" data-bs-target="#topics_modal">
            <p class="mb-3 ifave-h3 organic-margin text-decoration-underline link-primary"> Select location</p>
        </a>
        @if(count($get_last_three_locations) > 0)
        <div class="mb-3">
            @foreach($get_last_three_locations as $recent_links)
            <a class="mt-2 mb-2 link-secondary" rel="nofollow" href="/location/{{$recent_links->location_link}}">{{$recent_links->location}}</a>&nbsp;
            @endforeach
        </div>
        @endif
        <div class="container position-relative fav_tracks_parent">
            <div class="position-absolute fav_tracks">
                <div class="container mt-5">
                    <table class="table table-bordered rounded shadow border-blue user_faves_track">
                        <thead>
                            <td>
                                <p class="fs-6"><b>My faves</b></p>
                            </td>
                            @auth
                            <td>
                                <p><a rel="nofollow" class="fs-6 link-secondary" data-bs-toggle="modal" data-bs-target="#myfavetrack">All my faves</a></p>
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
        <h1 class="mb-3 ifave-h1" id="display_topic_name"></h1>
    </div>
    <!-- <div class="container position-relative mb-4 mt-5">
        <i onclick="scrollRight()" class="fa fa-4x fa-angle-double-right position-absolute right-scroll-btn" aria-hidden="true"></i>
        <i onclick="scrollLeftcont()" class="fa fa-4x fa-angle-double-left position-absolute left-scroll-btn" aria-hidden="true"></i>
        <div class="container fixed-width d-flex " id="scrollContainer">

        </div>
    </div> -->
    <div class="container text-center mb-5">
        <p class="ifave-h3"><a class="text-decoration-underline link-primary" data-bs-toggle="modal" id="open_search_category_modal" data-bs-target="#all_categories">All categories</a></p>
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
                <div class="text-center" id="onpageload-loader">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
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