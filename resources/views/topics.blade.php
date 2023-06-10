@include('layouts.app')
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">LOGO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-fex  justify-content-center">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Topics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Contacts</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav> -->

<input type="hidden" value="{{$get_topic->id}}" name="topic_id" id="topic_id">
<input type="hidden" value="{{$header_info}}" name="topic_name" id="topic_name">
<!-- {{ Route::currentRouteName() }} -->
<div class="container mt-5">
    <div class="text-center">
        <h2 class="mb-3" id="display_topic_name"></h2>
    </div>
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_questions" class="form-control" placeholder="look for more questions within this topic">
                <!-- <button class="btn btn-primary">Search</button> -->
                <div class="set_suggestion_height mt-3 d-none">



                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="container">
                <table class="table table-bordered">
                    <thead >
                        <th>My Votes</th>
                        @auth
                        <td><a href="">My Votes Track</a></td>
                        @else
                        <td>Register to keep track of your votes</td>
                        @endauth
                    </thead>
                </table>
            </div>
            <div class="container border p-2 m-2">
                <p>Best comments in this topic</p>
                <ol>
                    <li>Lena85 (295 upvotes)</li>
                    <li>Dansky (285 upvotes)</li>
                    <li>Supermind (275 upvotes)</li>
                    <li>Quatorze14 (265 upvotes)</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="">
            <div class="row mt-5" id="display_questions">


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