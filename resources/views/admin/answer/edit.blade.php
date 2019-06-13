
@extends('layouts.app')

@section('content')

<div class="container">
     <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-bottom: 20px">
                <div class="card-header">{{ __('Update Answer') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('answer.update',$answer->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label">{{ __('Title') }}</label>

                            <div class="col-md-10">
                                <textarea id="name" type="text" class="form-control @error('title') is-invalid @enderror" name="title" required>{{ $answer->title }}</textarea>

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
                                <input type="number" name="order" class="form-control" value="{{$answer->order}}">
                            </div>
                        </div>
                        @if($check_textarea==0 or $answer->use_textarea==1)
                        <div class="form-check row">
                            <div class="col-md-6 offset-md-2">
                                <input type="checkbox" class="form-check-input" {{$answer->use_textarea==1?"checked":""}} id="use-textarea" name="use_textarea" value="1">
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
        
    </div>
</div>
@endsection
