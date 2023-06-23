@include('layouts.app')

<input type="hidden" value="{{$get_topic->id}}" name="topic_id" id="topic_id">
<input type="hidden" value="{{$header_info}}" name="topic_name" id="topic_name">
<!-- {{ Route::currentRouteName() }} -->
<div class="container mt-5">
    <div class="text-center">
        <!-- <a href="" data-bs-toggle="modal" data-bs-target="#topics_modal">
            <h3 class="mb-3">Select your location</h3>
        </a> -->
        <div class="container position-relative fav_tracks_parent">
            <div class="position-absolute fav_tracks">
                <div class="container">
                    <table class="table table-bordered">
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
                <!-- <button class="btn btn-primary">Search</button> -->
                <div class="set_suggestion_height mt-3 d-none">



                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="">
            <div class="row mt-5" id="display_questions">


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