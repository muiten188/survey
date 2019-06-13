
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-5">
            <div class="card" style="margin-bottom: 20px">
                <div class="card-header">{{ __('Add Answer') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('answer.store') }}">
                        @csrf
                        <input type="hidden" name="question_id" value="{{$question->id}}">
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label">{{ __('Title') }}</label>

                            <div class="col-md-10">
                                <textarea id="name" type="text" class="form-control @error('title') is-invalid @enderror" name="title" required>{{ old('title') }}</textarea>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="order" class="col-md-2 col-form-label">{{ __('Order') }}</label>

                            <div class="col-md-4">
                                <input type="number" id="order" name="order" class="form-control">
                            </div>
                        </div>
                        @if($check_textarea==0)
                        <div class="form-check row">
                            <div class="col-md-6 offset-md-2">
                                <input type="checkbox" class="form-check-input" id="use-textarea" name="use_textarea" value="1">
                                <label class="form-check-label" for="use-textarea">Use textarea</label>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Answer') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="card">
                <div class="card-header">{{ __('Answers') }}</div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <p>Question: {{$question->title}}</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Title</th>
                                <th>Use textarea</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($answers as $key => $value) 
                            <tr>
                                <td>{{$value->order}}</td>
                                <td>{{$value->title }}</td>
                                <td>{{$value->use_textarea==0?"No":"Yes" }}</td>
                                <td width="150">
                                    <form class="delete" action="{{ route('answer.destroy',$value->id) }}" method="POST">
                                        <a class="btn btn-primary btn-sm" href="{{ route('answer.edit',$value->id) }}">Edit</a>
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete the record {{ $value->id }} ?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
