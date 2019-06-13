@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Question') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('question.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Group</label>

                            <div class="col-md-9">
                                <select class="form-control" name="group">
                                    <option value="1">One Time Only</option>
                                    <option value="2">Normal</option>
                                    <option value="3">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Question Type</label>

                            <div class="col-md-9">
                                <select class="form-control" id="question-type" name="type">
                                    <option value="1">Single Choice</option>
                                    <option value="2">Textbox</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Question') }}</label>

                            <div class="col-md-9">
                                <textarea id="name" type="text" class="form-control @error('title') is-invalid @enderror" name="title" required>{{ old('title') }}</textarea>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Answers') }}</label>

                            <div class="col-md-9 answer_radio">
                                <div id="answer_radio">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="text" name="answer[]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0)" class="float-right btn btn-danger btn-sm answer-delete">X</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="text" name="answer[]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0)" class="float-right btn btn-danger btn-sm answer-delete">X</a>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-success btn-sm" id="add-answer">Add Answer</a>
                            </div>
                             <div class="col-md-9 answer_text">
                                <div id="answer_text">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="answer_text" class="form-control">
                                        </div>
                                       
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary float-right">
                                    {{ __('Save') }}
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
@section('script')
<script type="text/javascript">
    $('.answer_text').hide();
    $("#add-answer").click(function (event) {
        var html = $( "#answer_radio .row").html();
        $("#answer_radio").append('<div class="row">'+html+'</div>');
    });
    $("body").on('click','.answer-delete',function(event){
        $(this).parent().parent().remove();
    });
    $("#question-type").change(function (event) {
        var data = $(this).val();
        if(data=='1'){
            $('.answer_radio').show();
            $('.answer_text').hide();
        }else{
            $('.answer_radio').hide();
            $('.answer_text').show();
        }
    });
</script>
@stop