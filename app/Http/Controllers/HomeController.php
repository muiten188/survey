<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\UserAnswer;
use App\Answer;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    public $dataJson;
    public $q1;
    public $options;
    public $option;
    public $option_answer_id;
    public $data;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->dataJson = json_decode(file_get_contents('survay.json'), true);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        if(!empty($this->dataJson["sequence"]["random"])){
            $data = $this->random($this->dataJson["sequence"]["random"]);
        }elseif(!empty($this->dataJson["sequence"]["time_condition"])){

        }else{
            $id = array_keys($this->dataJson['sequence'])[0]; 
            $data = $this->data($id);
            session()->pull('data');
            session()->put('data',$this->dataJson['sequence'][$id]['options']);
        }
        session()->put('key',10);
         
        $questions = Question::paginate(1);
        return view('home.index', [
            'questions' => $questions
        ]);
    }

    public  function test(){
        dd(session()->all());
    }

    public function store(Request $request) {
        $error = [];
        $data = [];
        foreach ($request->question as $key => $value) {
            $answer = 'answer_' . $key;
            $answer = $request->{$answer};
            if ($answer) {
                if (!$error) {
                    $question = Question::where('id', $key)->first();
                    $request['question'] = $question->title;
                    if ($question->type == 1) {
                        $answer = Answer::where('id', $answer)->first();
                        $request['answer'] = $answer->title;
                    } else {
                        $request['answer'] = $answer;
                    }

                    UserAnswer::create($request->all());
                    return redirect()->route('home.index', ['page' => $request->page + 1])
                                    ->with('success', 'You have completed.');
                }
            }

            return redirect()->route('home.index', ['page' => $request->page])
                            ->with('danger', 'Please check again.');
        }
    }

    public function data($id) 
    {
        $question = Question::where('id', $id)->first();
        $data = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
                'type' => (int) $question->type,
                'answers' => $question->answers
            ]
        ];
        return $data;
    }
    public function question()
    {
        $id = array_keys($this->dataJson['sequence'])[0]; 
        $data = $this->data($id);
        session()->pull('data');
        session()->put('data',$this->dataJson['sequence'][$id]['options']);
    }

    public function random($ids) {
        $key = array_rand($ids);
        $question = Question::where('id', $ids[$key])->first();
        $data = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
                'type' => (int) $question->type,
                'answers' => $question->answers
            ]
        ];
        return $data;
    }
    public function time_condition()
    {

    }

}
