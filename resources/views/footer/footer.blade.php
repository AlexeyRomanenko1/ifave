<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<!-- <footer class="footer text-center">
   <p class="p-5 m-2"></p>
   <div class="container text-center">
      <p>&copy; {{ date('Y') }} iFave<sup>&reg;</sup> All rights reserved.</p>
   </div>
</footer> -->
<div class="container mt-5">
   <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <p>&copy; {{ date('Y') }} iFave<sup>&reg;</sup> All rights reserved.</p>
      <ul class="nav col-md-4 justify-content-center list-unstyled d-flex">
         <li class="ms-3"><a class="" rel="nofollow" target="_blank" href="https://www.facebook.com/people/Ifavecom/61553178323176/"><i class="fa fa-facebook-square text-black-50" aria-hidden="true"></i></a></li>
         <li class="ms-3"><a class="" rel="nofollow" target="_blank" href="https://www.linkedin.com/company/ifave-com/"><i class="fa fa-linkedin-square text-black-50" aria-hidden="true"></i></a></li>
         <!-- <li class="ms-3"><a class="" href="#"><svg class="bi" width="24" height="24">
                  <use xlink:href="#facebook"></use>
               </svg></a></li> -->
      </ul>
      <ul class="nav col-md-4 justify-content-end">
         <li class="nav-item"><a href="/" class="nav-link px-2 text-black-50 text-decoration-none">The World</a></li>
         <li class="nav-item"><a rel="nofollow" href="#" class="nav-link px-2 text-black-50 text-decoration-none" data-bs-toggle="modal" data-bs-target="#topics_modal">All Location</a></li>
         <li class="nav-item"><a href="/blog" class="nav-link px-2 text-black-50 text-decoration-none">Blog</a></li>
         <li class="nav-item"><a href="/about-us" class="nav-link px-2 text-black-50 text-decoration-none">About Us</a></li>
         <li class="nav-item"><a href="/contact-us" class="nav-link px-2 text-black-50 text-decoration-none">Contact</a></li>
      </ul>
   </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- jquery cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
@if(Route::currentRouteName()== '/')
<script src="{{ asset('js/components/index.js')}}"></script>
<script src="{{ asset('js/components/the_world.js')}}"></script>
@endif
@if(Route::currentRouteName() =='topic_name' )
<script src="{{ asset('js/components/topics.js')}}"></script>
@endif

<script src="{{ asset('js/components/search_topics.js')}}"></script>
<script src="{{ asset('js/components/search_bloggers.js')}}"></script>


@if (request()->route()->getName() == 'comments_route')
<script src="{{ asset('js/components/comments.js')}}"></script>
@endif
@if (request()->route()->getName() == 'blog_details')
<script src="{{ asset('js/components/posts_details.js')}}"></script>

@endif
@if (request()->route()->getName() == 'create-blog-index' || request()->route()->getName() == 'create_blog_topic_question')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<!-- <script src="{{ asset('src/jquery.richtext.js')}}"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="{{ asset('js/froala_editor.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/align.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_beautifier.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_view.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/colors.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/draggable.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/emoticons.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_size.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_family.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/file.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image_manager.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/line_breaker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/link.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/lists.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_format.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/video.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/url.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/entities.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/char_counter.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/inline_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/save.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/fullscreen.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quick_insert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quote.min.js')}}"></script>
<!-- <script src="{{ asset('select_src/jquery-customselect.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('js/components/create_blog.js')}}"></script>
@endif
@if(request()->route()->getName() == 'editBlog')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<!-- <script src="{{ asset('src/jquery.richtext.js')}}"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="{{ asset('js/froala_editor.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/align.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_beautifier.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_view.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/colors.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/draggable.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/emoticons.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_size.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_family.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/file.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image_manager.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/line_breaker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/link.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/lists.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_format.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/video.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/url.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/entities.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/char_counter.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/inline_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/save.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/fullscreen.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quick_insert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quote.min.js')}}"></script>
<!-- <script src="{{ asset('select_src/jquery-customselect.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('js/components/edit_blog.js')}}"></script>
@endif
@if (request()->route()->getName() == 'blog' || request()->route()->getName() == 'filter_blog')
<!-- <script src="{{ asset('select_src/jquery-customselect.js')}}"></script> -->
<script src="https://code.jquery.com/jquery-migrate-3.4.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('slick/slick.js?v2022')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/components/blogs.js')}}"></script>
@endif
@if (request()->route()->getName() == 'update-profile')
<script src="{{ asset('js/components/user_profile.js')}}"></script>
@endif
@if(request()->route()->getName()=='questions_details')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="{{ asset('js/froala_editor.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/align.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_beautifier.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/code_view.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/colors.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/draggable.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/emoticons.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_size.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/font_family.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/file.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/image_manager.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/line_breaker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/link.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/lists.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_format.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/paragraph_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/video.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/url.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/entities.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/char_counter.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/inline_style.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/save.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/fullscreen.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quick_insert.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/quote.min.js')}}"></script>
<!-- <script src="{{ asset('select_src/jquery-customselect.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('js/components/question_details.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
@endif

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->
</body>

</html>