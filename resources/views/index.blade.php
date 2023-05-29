@include('headers.header')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
</nav>
<div class="container mt-5">
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" id="search_question_topics" class="form-control" placeholder="Look for a topic or questions">
                <button class="btn btn-primary">Search</button>
                <div class="set_suggestion_height mt-3">
                   

                
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="">
            <div class="row mt-5">
                <div class="col-md-6">
                    <h5 class="text-center">Popular questions</h5>
                    <div id="display_questions">
                    </div>

                    <!-- <div class="question">
                            <h6 class="p-3 border-bottom">Q: Best movie ever (421 votes)</h6>
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(46 votes)</li>
                                    <li class="hover"><b>Place </b>(41 votes)</li>
                                    <li class="hover"><b>Place </b>(34 votes)</li>
                                </ol>
                                <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Show More Answers
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="container border mt-1">
                        <div class="question">
                            <h6 class="p-3 border-bottom">Q: Best horror films (356 votes)</h6>
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(26 votes)</li>
                                    <li class="hover"><b>Place </b>(24 votes)</li>
                                    <li class="hover"><b>Place </b>(19 votes)</li>
                                </ol>
                                <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Show More Answers
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="container border mt-1">
                        <h6 class="p-3 border-bottom">Q: Best Comedy (289 votes)</h6>
                        <div class="question">
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(34 votes)</li>
                                    <li class="hover"><b>Place</b>(32 votes)</li>
                                    <li class="hover"><b>Place</b>(29 votes)</li>
                                </ol>
                                <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Show More Answers
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">Popular topics</h5>
                    <div id="popular_topics">

                    </div>
                    <!-- <div class="container border mt-1">
                        <div class="question">
                            <h6 class="mt-1">Topic 1</h6>
                            <hr>
                            <h6 class="p-3 border-bottom">Q: Best movie ever (421 votes)</h6>
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(46 votes)</li>
                                    <li class="hover"><b>Place </b>(41 votes)</li>
                                    <li class="hover"><b>Place </b>(34 votes)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="container border mt-1">
                        <div class="question">
                            <h6 class="mt-1">Topic 2</h6>
                            <hr>
                            <h6 class="p-3 border-bottom">Q: Best horror films (356 votes)</h6>
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(26 votes)</li>
                                    <li class="hover"><b>Place </b>(24 votes)</li>
                                    <li class="hover"><b>Place </b>(19 votes)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="container border mt-1">
                        <div class="question">
                            <h6 class="mt-1">Topic 3</h6>
                            <hr>
                            <h6 class="p-3 border-bottom">Q: Best Comedy (289 votes)</h6>
                            <div class="suggestions">
                                <ol>
                                    <li class="hover"><b>Place </b>(34 votes)</li>
                                    <li class="hover"><b>Place </b>(32 votes)</li>
                                    <li class="hover"><b>Place </b>(29 votes)</li>
                                </ol>
                            </div>
                        </div>
                    </div> -->
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