
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">{{ __('Answer Member') }}</div>

        <div class="card-body">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <td>Date</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($answers as $key => $value)
                        <tr>
                            <td>
                                {{$value->user->name}}
                            </td>
                            <td>
                                {{$value->question}}
                            </td>
                            <td>
                                {{$value->answer}}
                            </td>
                            <td>
                                {{$value->created_at }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {!! $answers->links() !!}
        </div>
    </div>
</div>
@endsection
