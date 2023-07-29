@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <!-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif -->
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="alert alert-error">
                        {{ session()->get('error') }}
                    </div>
                    @endif
                </div>
                <div class="text-center">
                    <button class="btn btn-danger p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Import Questions</button>
                    <button class="btn btn-warning p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal2">Import Answers</button>
                    <button class="btn btn-primary p-1 m-1" data-bs-toggle="modal" data-bs-target="#exampleModal3">Import Images</button>
                    <a href="/blog-requests"class="btn btn-dark p-1 m-1">Blog Requests</a>
                    <form method="POST" action="{{url('export_users')}}">
                        @csrf
                        <button type="submit" class="btn btn-success p-1 m-1">Export Users</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <table id="questions" class="display" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Answers</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="questions_table_body">
            @php
            $i=1;
            @endphp
            @foreach($query as $answers)
            <tr>
                <td>{{$i}}</td>
                <td>{{$answers->answers}}</td>
                <td>{{$answers->questions_category}}</td>
                <td><i class="fa fa-bars text-success m-1 p-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#edit_question_modal" onclick="edit_modal_form({{ $answers->id }})"></i><i class="fa fa-trash m-1 text-danger p-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#delete_question_modal" onclick="delete_modal_form({{ $answers->id }})"></i></td>
            </tr>
            @php
            $i=$i+1;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Answers</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- Modal for importing questions -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Questions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{url('import_questions')}}" class="form-control" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="import_questions" id="inputGroupFile02">
                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import Questions</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal for importing answers -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Answers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{url('import_answer')}}" class="form-control" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="import_answer" id="inputGroupFile02">
                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import Answers</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit_question_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('update_dashboard_answer')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="dashboard_answer_id" id="dashboard_answer_id">
                    <div class="mb-3">
                        <label for="question" class="form-label">Answer</label>
                        <input type="text" class="form-control" name="answer" id="answer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Answer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="delete_question_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('delete_dashboard_answer')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this answer?</h5>
                    <input type="hidden" name="delete_answer_id" id="delete_answer_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete Answer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@include('layouts.footer')
<script src="{{ asset('js/components/dashboard_answers.js')}}"></script>