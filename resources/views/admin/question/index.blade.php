@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">{{ __('Questions') }} <a href="{{ route('question.create') }}" class="btn btn-primary btn-sm float-right">Add Question</a></div>

        <div class="card-body">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            @foreach($questions as $key=>$value)
                <div class="row" style="margin-bottom: 20px; border-bottom: 1px solid #ccc;">
                    <div class="col-md-12">
                        <h6>{{$value->title}} (ID: {{$value->id}})</h6>

                        <div class="float-left">
                            <input type="hidden" name="question[{{$value->id}}]" value="{{$value->title}}">
                            @if(!empty($value->answers))
                                @if($value->type == 1)
                                @foreach ($value->answers as $key => $answer) 
                                <div class="form-radio" style="margin-bottom: 10px">
                                    <input type="radio" disabled name="answer_radio_{{$value->id}}" class="form-radio-input" id="answer_{{$answer->id}}" value="{{$answer->title}}">
                                    <label class="form-check-label" for="answer_{{$answer->id}}">{{$answer->title}}</label>
                                </div>

                                @endforeach
                                @else
                                <div class="form-group" style="margin-bottom: 10px">
                                    <textarea class="form-control" disabled style="width:400px" name="answer_text_{{$value->id}}" ></textarea>

                                </div>
                                @endif
                            @endif    
                        </div>
                        <div class="float-right">
                            <form class="delete" action="{{ route('question.destroy',$value->id) }}" method="POST">
                                <a class="btn btn-success btn-sm" href="{{ route('question.edit',$value->id) }}">Edit</a>
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete the record {{ $value->id }} ?')">Delete</button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
            {!! $questions->links() !!}
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