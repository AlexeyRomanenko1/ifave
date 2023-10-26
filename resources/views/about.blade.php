@include('layouts.app')
<div class="container">
    <div class="row mt-5">
        <div class="col-lg-8 offset-lg-2">
            <div class="text-center">
                <img src="/images/about-us-img.jpg" alt="About Us" class="img-fluid mb-4">
            </div>
            <h2>About Us: Discover, Compare, and Share with iFave</h2>
            <p>Welcome to iFave, your ultimate reference for discovering, comparing, and sharing the best of everything. We're a community-driven platform that empowers users with unbiased opinions and statistical insights. Our goal is to provide you with a single source for understanding what the world truly likes and dislikes.</p>



            <h3 class="mt-4">Unveiling Authentic Rankings</h3>
            <p>Do Top 5, 10, or 100 lists intrigue you? They captivate us too. Yet, who curates these lists? Some bloggers, experts, critics? Can we trust their taste and analysis? Enter iFave, where users rank freely and remain uninfluenced by pre-made lists or "suggestions".</p>

            <h3 class="mt-4">All My Faves Feature, Your Unique Portrait</h3>
            <p>Imagine having a personalized collection of your favorite things and places. All My Faves feature allows you to save and track your preferences while building a reflection of your unique personality. This comes in handy for recalling past experiences. Whether it's recommending a bakery in Paris you visited years ago or a restaurant you stumbled upon last month, you'll have all your cherished memories at your fingertips.</p>

            <!-- Insert the "POPULAR CATEGORIES" card here -->
            <div class="card mt-4">
                <div class="card-header text-center">
                    <h6>POPULAR CATEGORIES</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($popular_questions as $popular_question)
                        <li class="list-group-item"><a href="/category/{{str_replace(' ','-',$popular_question->topic_name)}}/{{str_replace(' ','-',$popular_question->question)}}">{{$popular_question->question}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- End of "POPULAR CATEGORIES" card -->

            <h3 class="mt-4">Engage, Uncover, Discuss</h3>
            <p>Beyond rankings lies an engaging realm of conversation. Our blogging and commenting platform fosters discussions from the world's most famous personalities to local shops and dentists. Gain local insights and uncover hidden gems beyond the beaten path.</p>

            <h3 class="mt-4">Join the iFave Community</h3>
            <p>iFave is more than just a platform; it's a community of like-minded individuals who are passionate about discovering and sharing their favorite things. Embrace curiosity, explore new experiences, and proudly share discoveries with the world.</p>

            <p>Thank you for being part of the iFave journey. Together, we're rewriting the way we discover, compare, and celebrate the world's treasures – one unbiased opinion at a time.</p>
        </div>
    </div>
</div>

@include('footer.footer')