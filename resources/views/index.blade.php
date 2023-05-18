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
                <input type="text" class="form-control" placeholder="Look for a topic or questions">
                <button class="btn btn-primary">Search</button>
            </div>

        </div>

    </div>
    <div class="container">
        <div class="">
            <div class="row mt-5">
                <div class="col-md-6">
                    <h5 class="text-center">Popular questions</h5>
                    <div class="container border mt-1">
                        <div class="question">
                            <h6 class="p-3 border-bottom">Q: Best movie ever (421 votes)</h6>
                            <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
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
                            <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
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
                                <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
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
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">Popular topics</h5>
                    <div class="container border mt-1">
                        <div class="question">
                            <h6 class="mt-1">Topic 1</h6>
                            <hr>
                            <h6 class="p-3 border-bottom">Q: Best movie ever (421 votes)</h6>
                            <div class="suggestions">
                                <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
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
                                <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
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
                                <!-- <input type="text" class="form-control mb-1" placeholder="Search options"> -->
                                <ol>
                                    <li class="hover"><b>Place </b>(34 votes)</li>
                                    <li class="hover"><b>Place </b>(32 votes)</li>
                                    <li class="hover"><b>Place </b>(29 votes)</li>
                                </ol>
                            </div>
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
                <h5 class="modal-title" id="exampleModalLabel">Best movie ever (421 votes)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container border mt-1">
                    <h6 class="p-3 border-bottom">Q: Best Comedy (289 votes)</h6>
                    <div class="question">
                        <div class="modal-suggestions">
                            <input type="text" class="form-control mb-1" placeholder="Search options">
                            <ol>
                                <li class="hover"><b>Rules of the Game, The </b></li>
                                <li class="hover"><b>Signin'in the Rain </b></li>
                                <li class="hover"><b>City Lights </b></li>
                                <li class="hover"><b>Godfather, The </b></li>
                                <li class="hover"><b>Sunrise </b></li>
                                <li class="hover"><b>Searchers, The </b></li>
                                <li class="hover"><b>Seven Samurai</b></li>
                                <li class="hover"><b>Singin' in the Rain </b></li>
                                <li class="hover"><b>Jeanne Dielman, 23 Quai du Commerce, 1080 Bruxelles </b></li>
                                <li class="hover"><b>Apocalypse Now </b></li>
                                <li class="hover"><b>Bicycle Thieves </b></li>
                                <li class="hover"><b>Taxi Driver </b></li>
                                <li class="hover"><b>Persona </b></li>
                                <li class="hover"><b>Passion of Joan of Arc, The </b></li>
                                <li class="hover"><b>Breathless</b></li>
                                <li class="hover"><b>In the Mood for Love</b></li>
                                <li class="hover"><b>Battleship Potemkina </b></li>
                                <li class="hover"><b>Atalante, L' </b></li>
                                <li class="hover"><b>Man with a Movie Camera, The </b></li>
                                <li class="hover"><b>Mirror </b></li>
                                <li class="hover"><b>Rashomon</b></li>
                                <li class="hover"><b>Psycho</b></li>
                                <li class="hover"><b>400 Blows, The</b></li>
                                <li class="hover"><b>Andrei Rublev </b></li>
                                <li class="hover"><b>Au hasard Balthazar </b></li>
                                <li class="hover"><b>Some Like it Hot </b></li>
                                <li class="hover"><b>Ordet </b></li>
                                <li class="hover"><b>Raging Bull</b></li>
                            </ol>
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