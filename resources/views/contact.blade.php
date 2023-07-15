@include('layouts.app')
<div class="container">
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
            <textarea class="form-control" id="message" name="message" rows="3" value="{{ old('message') }}">{{ old('message') }}</textarea>
            @error('message')
            <div class="error"><b class="text-danger">{{ $message }}</b></div>
            @enderror
        </div>
        <div class="mb-3">
            <button class="btn btn-primary">Send</button>
        </div>
    </form>
</div>
@include('footer.footer')