<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/images/question_images/IFAVE_PNG.png">
    <!-- CSRF Token -->
    @if(isset($keywords))
    <meta name="keywords" content="{{$keywords}}">
    <meta name="description" content="{{$meta_description}}" />
    <meta property="og:title" content="Ifave - Explore Surveys and Blogs on ifave.com" />
    <meta id="meta-property" property="og:description" content="{{$meta_description}}" />
    <!-- <meta property="og:description" content="Engage in surveys, vote on answers, and explore insightful blogs on ifave.com. Join a dynamic online community of opinions and ideas." /> -->
    @else
    <meta property="keywords" content="Ifave - Explore Surveys and Blogs on ifave.com" />
    <meta property="description" content="Engage in surveys, vote on answers, and explore insightful blogs on ifave.com. Join a dynamic online community of opinions and ideas." />
    @endif

    @if(request()->route() && (request()->route()->getName() == 'questions_details') && isset($location) && $location == 'The World')
    <link rel="canonical" href="https://ifave.com/category/{{$cononical_location}}/{{$cononical_category}}">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'questions_details') && isset($location) && $location != 'The World')

    <meta name="robots" content="noindex">
    @endif
    @if (request()->route() && (request()->route()->getName() == '/'))
    <link rel="canonical" href="https://ifave.com">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'blog') || request()->route() && (request()->route()->getName() == 'blogger_location_filter') || request()->route() && (request()->route()->getName() == 'filter_blog'))
    <link rel="canonical" href="https://ifave.com/blog/">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'comments_route'))
    <meta name="robots" content="noindex">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'blog_details') && isset($slug))
    <link rel="canonical" href="https://ifave.com/{{$slug}}">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'topic_name') && isset($topicName) && $topicName != 'The World')
    <meta name="robots" content="noindex">
    @elseif(request()->route() && (request()->route()->getName() == 'topic_name') && isset($topicName) && $topicName == 'The World')
    <link rel="canonical" href="https://ifave.com/">
    @endif
    <meta property="og:url" content="https://ifave.com" />
    <meta property="og:image" content="https://ifave.com/images/question_images/IFAVE_PNG.png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($page_title))
    <title>{{$page_title}}</title>
    @elseif(!isset($page_title) && request()->route() && (request()->route()->getName() == 'login'))
    <title>Secure Login at iFave.com - Access Your Favorites with Confidence</title>
    @elseif(!isset($page_title) && request()->route() && (request()->route()->getName() == 'register'))
    <title>Join iFave.com - Register for Exclusive Access to Premium Content</title>
    @else 
    <title>Questions Survey</title>
    @endif
    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- fontawsome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css">
    <!-- <link href="//db.onlinewebfonts.com/c/3e14931180b08416dd7c967a7163f8ea?family=Calibri" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/berlin_sans.css') }}" rel="stylesheet">
    @if (request()->route() && (request()->route()->getName() == 'create-blog-index' || request()->route()->getName() == 'create_blog_topic_question'))
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <!-- <link href="{{ asset('src/richtext.min.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css">
    <link rel="stylesheet" href="{{ asset('css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/froala_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/code_view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/emoticons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image_manager.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/line_breaker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/char_counter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/video.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullscreen.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/quick_insert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/file.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes/dark.css') }}">
    <!-- <link href="{{ asset('select_src/jquery-customselect.css') }}" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    @endif
    @if(request()->route() && request()->route()->getName() == 'editBlog' )
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <!-- <link href="{{ asset('src/richtext.min.css') }}" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/froala_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/code_view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/emoticons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image_manager.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/line_breaker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/char_counter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/video.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullscreen.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/quick_insert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/file.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes/dark.css') }}">
    <!-- <link href="{{ asset('select_src/jquery-customselect.css') }}" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    @endif
    @if(request()->route() && request()->route()->getName()=='questions_details')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/froala_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/code_view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/emoticons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image_manager.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/image.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/line_breaker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/char_counter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/video.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/fullscreen.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/quick_insert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/file.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes/dark.css') }}">
    @endif
    @if (request()->route() && (request()->route()->getName() == 'blog' || request()->route()->getName() == 'filter_blog'))
    <!-- <link href="{{ asset('select_src/jquery-customselect.css') }}" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css?v2022') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css?v2022') }}">
    @endif
    @if(request()->route() && request()->route()->getName()=='comments_route')

    <link href="{{ asset('css/comments.css') }}" rel="stylesheet">
    @endif
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z3Q2V0LDTH"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());



    gtag('config', 'G-Z3Q2V0LDTH');
</script>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark back-blue shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/ifave_log/ifave-logo.png" alt="iFave Main Logo" height="40px" width="150px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- <li class="nav-item">
                            <a href="" class="nav-link">Home</a>
                        </li> -->
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link">The World</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="" rel="nofollow" class="nav-link" data-bs-toggle="modal" data-bs-target="#topics_modal">All Locations</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a href="" class="nav-link">About</a>
                        </li> -->
                        <li class="nav-item">
                            <a href="{{ url('/blog') }}" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/create-blog') }}" class="nav-link">Create Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/about-us') }}" class="nav-link">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/contact-us') }}" class="nav-link">Contact</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a rel="nofollow" class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a rel="nofollow" class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a rel="nofollow" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a rel="nofollow" class="dropdown-item" href="/update-profile">
                                    Update Profile
                                </a>
                                <a rel="nofollow" class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-1">
            @yield('content')
        </main>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="topics_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">All Locations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row height d-flex justify-content-center align-items-center">
                        <div class="col-md-8">
                            <div class="search">
                                <i class="fa fa-search"></i>
                                <input type="text" id="search_topics" class="form-control" placeholder="Search">
                                <!-- <button class="btn btn-primary">Search</button> -->
                                <div class="set_suggestion_height_topics mt-3 rounded">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="all_bloggers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">All Bloggers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row height d-flex justify-content-center align-items-center">
                        <div class="col-md-8">
                            <div class="search">
                                <i class="fa fa-search"></i>
                                <input type="text" id="tosearch_blogger" class="form-control" placeholder="Search">
                                <!-- <button class="btn btn-primary">Search</button> -->
                                <div class="set_suggestion_height_bloggers mt-3 rounded">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myfavetrack" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">My Faves</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-2">
                        <button class="btn btn-primary personality-potrait" data-bs-toggle="tooltip" data-bs-placement="top" title="Personality potrail based on your faves">Generate Personality Potrait</button>
                    </div>
                    <h5 class="personality_headding"></h5>
                    <p class="personality_content"></p>
                    <table class="table" id="faves_table">
                        <thead>
                            <tr>
                                <!-- <th scope="col">#</th> -->
                                <th scope="col">My Fave</th>
                                <th scope="col">Category</th>
                                <th scope="col">Location</th>
                            </tr>
                        </thead>
                        <tbody id="faves_table_body">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sharemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Share Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <a id="facebook_share" class="btn  m-2" href=""><i class="fa fa-facebook-square" aria-hidden="true"></i></a><a id="twitter_share" class="btn m-2" href=""><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="top_comments_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small>(Upvotes here are the total of all upvotes in this location minus all downvotes.)</small>
                    <div class="row height d-flex justify-content-center align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="search">
                                <i class="fa fa-search"></i>
                                <input type="text" id="search_users_comments" class="form-control" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div id="top_comments_modal_body"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>