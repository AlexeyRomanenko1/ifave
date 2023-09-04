@include('layouts.app')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="justify-content-start">
                <p>
                    Welcome our Bloggers! <br><br>

                    iFave is a community of curious and open-minded people who know and love the places where they live (be it a small village, a metropolis or the whole world).
                    Our platform is made for those who like trying new things and are proud to share their discoveries. Our bloggers are a key part of all this.<br><br>

                    Blogging on iFave is fun because we are all about favorite things. We offer a great choice of topics. So just tell us about your faves.
                    You are welcome to give your opinions and arguments for who is the best, create your own top tens, inform the world about the news and trends within a certain category and much more.<br><br>
                </p>
                With us, you will participate in the economic growth of your region by improving the experiences of locals and tourists. You can also support your favorite entrepreneurs by spreading the word about them. You can promote their products and discounts without forgetting to give a thorough review of the premises and of their strengths and weaknesses.
                Our bloggers may accept payments, gifts, free services or other benefits from businesses they review. The important thing is to make proper disclosures and be honest with the readers.
                You are free to promote your website and social media accounts. You can also use affiliate links to reputable websites as long as you provide quality and useful content.<br><br>
            </div>
        </div>
        <div class="col-md-4">
            <img src="/images/Create-blog-page.jpg" class="img-fluid mt-5 mb-3" alt="...">
        </div>
        <div class="d-flex justify-content-start">
            <p>
                All posts are displayed on the Category pages and the best posts on the Location pages.
                Your rating is based on views and likes of your posts. Upvotes of your comments anywhere on our website are also included into your rating. Higher ratings result in a higher visibility.<br><br>

                Thank you and good luck!
            </p>
        </div>
    </div>
    <div class="text-center">
        <small><b>Note:</b> Before posting your blog, we kindly request you to update your profile with a photo, location, and bio. <a href="/update-profile">Update Profile</a><br>Your profile is a representation of yourself and helps our readers connect with you better. Adding a photo and your location adds a personal touch to your blog posts.</small><br><br>
        <h4 class="">Write a Blog Post</h4><br>
        <a class="link-opacity-100" data-bs-toggle="modal" data-bs-target="#blogging_tips">
            <h4>Blogging tips</h4>
        </a>
    </div>
    @php
    // Convert the JSON string to an array
    $postArray = json_decode($post_details, true);
    @endphp
    <input type="hidden" name="hidden_topic_id" id="hidden_topic_id" value="{{$postArray[0]['topic_id']}}">
    <input type="hidden" name="hidden_question_id" id="hidden_question_id" value="{{$postArray[0]['question_id']}}">
    <form id="blog_form" enctype="multipart/form-data">
        @csrf
    <input type="hidden" name="blog_id" value="{{$blog_id}}">
        <div class="mb-3">
            <label for="blog_title" class="form-label">Blog Title <b class="text-danger">*</b></label>
            <input type="text" class="form-control" id="blog_title" name="blog_title" maxlength="100" value="{{$postArray[0]['title']}}" disabled>


        </div>
        @if(isset($topic) && isset($question))
        <div class="mb-3">
            <label for="topic_name" class="form-label">Location</label>
            <input type="text" id="topic_name" class="form-control" name="topic_name" value="{{$topic}}" readonly />
            <input type="hidden" name="topic_id" value="{{$topic_id[0]}}">
        </div>
        <div class="mb-3">
            <label for="question" class="form-label">Category</label>
            <input type="text" id="question" class="form-control" name="question" value="{{$question}}" readonly />
            <input type="hidden" name="question_id" value="{{$question_id[0]}}">

        </div>
        @endif
        <div class="mb-3">
            <label for="tags" class="form-label">Tags<b class="text-danger">*</b></label><br>
            <input type="text" id="tags" class="form-control" name="tags" value="{{$postArray[0]['tags']}}" data-role="tagsinput" />
            <small id="tags_error" class="text-danger d-none">This fied is required</small>
            @error('tags')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="select_location" class="form-label">Location<b class="text-danger">*</b></label>
                    <select class="select-2 form-control" id="select_location" name="topic_id" aria-label="Select Location">
                        <!-- <option selected>Select Location</option> -->
                        @foreach($topics as $all_topic)
                        @if($all_topic->id==$postArray[0]['topic_id'])
                        <option value="{{$all_topic->id}}" selected>{{$all_topic->topic_name}}</option>
                        @else
                        <option value="{{$all_topic->id}}">{{$all_topic->topic_name}}</option>
                        @endif
                        @endforeach
                    </select>
                    <small id="location_error" class="text-danger d-none">This fied is required</small>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <div id="custom-select-category">
                        <label for="select_category" class="form-label">Category<b class="text-danger">*</b></label>
                        <select class="select-2 form-control custom-select-category" id="select_category" name="question_id" aria-label="Select Category" disabled>
                            <option selected disabled>Select Category</option>
                        </select>
                    </div>
                    <small id="category_error" class="text-danger d-none">This fied is required</small>
                </div>
            </div>
        </div>

        <div class="mb-3"></div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Featured Image<b class="text-danger"> ( 600x600 )*</b></label>
            <input class="form-control" type="file" id="formFile" name="featured_image">
            @error('featured_image')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror
            <div class="container mt-3">
                <img src="/images/posts/{{$postArray[0]['featured_image']}}" alt="" height="200px" width="200px">
            </div>
        </div>
        <div class="mb-3">
            <label for="edit" class="form-label">Blog Content<b class="text-danger">*</b></label>
            <textarea class="form-control" name="blog_content" id="edit" rows="3" data-upload-url="{{ route('upload_content_image') }}" value="{!! $postArray[0]['blog_content'] !!}">{!! $postArray[0]['blog_content'] !!}</textarea>
            <small id="content_error" class="text-danger d-none">This fied is required</small>
            @error('blog_content')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror

        </div>
        <button type="submit" class="btn btn-primary">Update Blog</button>
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="blogging_tips" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Blogging Tips</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <h4>Blogging Guidelines and Tips</h4>
                <h5>General posting guidelines</h5>
                <ul>
                    <li>We recommend posting articles of at least 1000 words (6000 characters) which rank higher in search engines.</li>
                    <li>All posts must be originally written for iFave.</li>
                    <li>Respect copywrite of text and images.</li>
                    <li>Blog tags (or keywords) are words or phrases that describe a blog post. They help search engines to find your post. Keep them short (usually 1-2 words). Do not use more than 7-8 tags.</li>
                    <li>Use rel=”sponsored” or rel=”nofollow” attributes on affiliate links.</li>
                </ul>

                <h5>Tips for writing an awesome blog post</h5>
                <ul>
                    <li><b>Engaging Headline:</b> Craft a catchy and informative headline that grabs readers' attention and encourages them to click and read your blog.</li>
                    <li><b>Clear Structure:</b> Organize your blog with clear headings, subheadings, and paragraphs. Use bullet points or numbered lists for easy readability.</li>
                    <li><b>Start Strong:</b> Begin your blog with a compelling introduction that hooks the reader and sets the tone for the rest of the post.</li>
                    <li><b>Use Conversational Tone:</b> Write in a friendly and conversational tone to make your blog relatable and engaging.</li>
                    <li><b>Add Visuals:</b> Include relevant images, infographics, or videos to break up the text and make your blog visually appealing.</li>
                    <li><b>Provide Value:</b> Offer valuable and actionable information, tips, or insights that benefit your readers.</li>
                    <li><b>Be Original:</b> Provide a unique perspective or angle on the topic to make your blog stand out from others in the same niche.</li>
                    <li><b>Edit and Proofread:</b> Before publishing, carefully edit and proofread your blog to ensure it is free of errors and flows smoothly.</li>
                    <li><b>Optimize for SEO:</b> Use relevant keywords and phrases naturally throughout your blog to improve its visibility on search engines.</li>
                    <li><b>Keep your content fresh and up to date.</b> Periodically review your posts.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('footer.footer')
<script>
    $(document).ready(function() {
        // Initialize your rich textarea editor plugin here

        // Set the previously submitted value
        var previousValue = "{{ old('blog_content') }}";
        $('#edit').val(previousValue);
    });
</script>