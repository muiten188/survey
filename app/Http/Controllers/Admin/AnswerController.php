<?php

namespace App\Http\Controllers\Admin;

use App\Answer;
use App\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AnswerController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $questions = Answer::paginate(20);
        return view('admin.question.index', [
            'questions' => $questions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'order' => 'required'
        ]);
        $request['author_id'] = Auth::user()->id;
        Answer::create($request->all());
        return redirect()->route('question.show', $request->question_id)
                        ->with('success', 'Answer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer) {
        $questions = \App\Answer::get();
        return view('admin.question.index', [
            'questions' => $questions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer) {
        $check_textarea = Answer::where('question_id', $answer->question_id)->where('use_textarea', 1)->count();
        return view('admin.answer.edit', ['answer' => $answer, 'check_textarea' => $check_textarea]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer) {
        $request->validate([
            'title' => 'required',
            'order' => 'required'
        ]);
        $answer->update($request->all());

        return redirect()->route('question.show', $answer->question_id)
                        ->with('success', 'Answer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $answer) {
        $answer->delete();

        return redirect()->route('question.show', $answer->question_id)
                        ->with('success', 'Answer deleted successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function member() {
        $answers = UserAnswer::paginate(20); 
        return view('admin.answer.member', [
            'answers' => $answers
        ]);
    }
}
