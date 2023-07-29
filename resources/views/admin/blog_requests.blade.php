@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <button class="btn btn-danger p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Import Questions</button>
        <button class="btn btn-warning p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal2">Import Answers</button>
        <button class="btn btn-primary p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal3">Import Images</button>
        <a href="/blog-requests" class="btn btn-dark p-1 m-1">Blog Requests</a>
        <form method="POST" action="{{url('export_users')}}">
            @csrf
            <button type="submit" class="btn btn-success p-1 m-1">Export Users</button>
        </form>
    </div>
</div>
<div class="container mt-4" id="posts_list">
    @foreach($posts as $post)
    <div class="row border border-blue mt-3 p-2 m-2">
        <div class="col-md-3">
            <img src="/images/posts/{{$post->featured_image}}" class="img-fluid" height="300px" width="300px" alt="">
        </div>
        <div class="col-md-9">
            <h4 class="mt-2"><a target="_blank" href="/blog-request/{{$post->slug}}">{{$post->title}}</a></h4>
            {!! substr(strip_tags($post->blog_content), 0, 700) !!}... <br><br>
            <small><b>Date:</b> {{ date('d-m-Y', strtotime($post->created_at)) }}</small><br>
            <small><b>Author:</b> {{$post->name}}</small>
        </div>
    </div>
    @endforeach
    <div class="pagination justify-content-center">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
@include('layouts.footer')