<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<footer class="footer text-center">
   <p class="p-5 m-2"></p>
   <div class="container text-center">
      <p>&copy; {{ date('Y') }} iFave<sup>&reg;</sup> All rights reserved.</p>
   </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- jquery cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
@if(Route::currentRouteName()!='topic_name')
<script src="{{ asset('js/components/index.js')}}"></script>
@else
<script src="{{ asset('js/components/topics.js')}}"></script>
@endif
<script src="{{ asset('js/components/search_topics.js')}}"></script>
<script src="{{ asset('js/components/search_bloggers.js')}}"></script>

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
<script src="{{ asset('select_src/jquery-customselect.js')}}"></script>
<script src="{{ asset('js/components/create_blog.js')}}"></script>
@endif
@if (request()->route()->getName() == 'blog' || request()->route()->getName() == 'filter_blog')
<script src="{{ asset('select_src/jquery-customselect.js')}}"></script>
<script src="https://code.jquery.com/jquery-migrate-3.4.0.min.js"></script>
<script src="{{ asset('slick/slick.js?v2022')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/components/blogs.js')}}"></script>
@endif
@if (request()->route()->getName() == 'update-profile')
<script src="{{ asset('js/components/user_profile.js')}}"></script>
@endif

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->
</body>

</html>