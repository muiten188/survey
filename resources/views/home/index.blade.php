@extends('layouts.app')

@section('content')
<div class="container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    @if ($message = Session::get('danger'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="card" style="margin-bottom: 10px">
        <div class="card-header">{{ __('Questions') }}</div>

        <div class="card-body">
            <form method="POST" action="{{ route('home.store') }}">
                @csrf

                @foreach ($questions as $key => $value) 
                @if(count($value->answers)>0)
                <div class="row" style="margin-bottom: 20px; border-bottom: 1px solid #ccc;">
                    <div class="col-md-12">
                        <h6>{{$value->title}}</h6>

                        <div class="float-left">
                            <input type="hidden" name="page" value="{{$questions->currentPage()}}">
                            <input type="hidden" name="question[{{$value->id}}]" value="{{$value->title}}">
                            @if($value->type == 1)
                            @foreach ($value->answers as $key => $answer) 
                            <div class="form-radio" style="margin-bottom: 10px">
                                <input type="radio" name="answer_{{$value->id}}" class="form-radio-input" id="answer_{{$answer->id}}" value="{{$answer->id}}">
                                <label class="form-check-label" for="answer_{{$answer->id}}">{{$answer->title}}</label>
                            </div>

                            @endforeach
                            @else
                            <div class="form-group" style="margin-bottom: 10px">
                                <textarea class="form-control" style="width:400px" name="answer_{{$value->id}}" id="answer_{{$value->answers[0]->id}}"></textarea>

                            </div>
                            @endif
                        </div>
                    </div>

                </div>
                @endif
                @endforeach
                @if($questions)
                <input type="submit" class="btn btn-primary">
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
setTimeout(function(){ 
    $(".alert").remove(); 
}, 3000);
</script>
@stop