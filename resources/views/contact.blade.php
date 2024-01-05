@include('layouts.app')
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlTbRfzzdAVV7sApqO6_AEdqG6ElvsdxI&callback=console.debug&libraries=maps,marker&v=beta">
</script>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <form action="{{url('/contact-us')}}" method="POST">
                @csrf
                <div class="text-center mt-5">
                    <h4 class="text-decoration-underline">Contact Us</h4>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name') }}">
                    @error('name')
                    <div class="error"><b class="text-danger">{{ $message }}</b></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address <b class="text-danger">*</b></label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}">
                    @error('email')
                    <div class="error"><b class="text-danger">{{ $message }}</b></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{ old('subject') }}">
                    @error('subject')
                    <div class="error"><b class="text-danger">{{ $message }}</b></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message <b class="text-danger">*</b></label>
                    <textarea class="form-control" onpaste="return false" id="message" name="message" cols="30" rows="10" maxlength="500" value="{{ old('message') }}">{{ old('message') }}</textarea>
                    <small class="text-primary comment-warn">0/500</small><br>
                    @error('message')
                    <div class="error"><b class="text-danger">{{ $message }}</b></div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="mt-5" id="map" style="height: 400px;">
                <gmp-map center="46.9009895324707,-71.12779235839844" zoom="14" map-id="DEMO_MAP_ID">
                    <gmp-advanced-marker position="46.9009895324707,-71.12779235839844" title="My location">
                    </gmp-advanced-marker>
                </gmp-map>
            </div>
            <div class="card mt-5">
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
        </div>
    </div>

</div>

@include('footer.footer')
<script>
    $('#message').on('keyup', function() {
        $('.comment-warn').html($(this).val().length + '/500')
    })
</script>