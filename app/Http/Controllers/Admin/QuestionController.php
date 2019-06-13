<?php

namespace App\Http\Controllers\Admin;

use App\Question;
use App\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class QuestionController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $questions = Question::orderBy('title', 'asc')->paginate(30);
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
            'title' => 'required'
        ]);
        $request['user_id'] = Auth::user()->id;
        $id = Question::create($request->all())->id;
        if ($request->type == 1) {
            foreach ($request->answer as $k => $value) {
                if (!empty($request->answer[$k])) {
                    $answer = new Answer();
                    $answer->question_id = $id;
                    $answer->title = $value;
                    $answer->save();
                }
            }
        } else {
            $answer = new Answer();
            $answer->question_id = $id;
            $answer->title = $request->answer_text;
            $answer->save();
        }
        return redirect()->route('question.index')
                        ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question) {
        $answers = Answer::where('question_id', $question->id)->get();
        $check_textarea = Answer::where('question_id', $question->id)->where('use_textarea', 1)->count();
        return view('admin.question.show', ['answers' => $answers, 'question' => $question, 'check_textarea' => $check_textarea]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question) {
        return view('admin.question.edit', ['question' => $question]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question) {
        $request->validate([
            'title' => 'required',
        ]);

        $question->update($request->all());
        Answer::where('question_id',$question->id)->delete();
        if ($request->type == 1) {
            foreach ($request->answer as $k => $value) {
                if (!empty($request->answer[$k])) {
                    $answer = new Answer();
                    $answer->question_id = $question->id;
                    $answer->title = $value;
                    $answer->save();
                }
            }
        } else {
            $answer = new Answer();
            $answer->question_id = $question->id;
            $answer->title = $request->answer_text;
            $answer->save();
        }
        return redirect()->route('question.index')
                        ->with('success', 'Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question) {
        $question->delete();

        return redirect()->route('question.index')
                        ->with('success', 'Question deleted successfully');
    }

}
