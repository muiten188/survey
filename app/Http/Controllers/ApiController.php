<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use App\UserAnswer;
use App\User;

class ApiController extends Controller {

    public $successStatus = 200;
    public $dataJson;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->dataJson = json_decode(file_get_contents('survay.json'), true);
    }

    public function index(Request $request) {
        $user = User::where('name', $request->client)->first();
        if (!$user) {
            $user = new User;
            $user->name = $request->client;
            $user->ap_mac_address = $request->ap_mac_address;
            $user->user_agent = $request->agent;
            $user->save();
        }
        if ($user->history) {
            $this->dataJson = unserialize($user->history);
        }

        if (!empty($this->dataJson["random"])) {
            $data = $this->random($this->dataJson["random"]);
            $this->saveJson($request->client, $this->dataJson["random"]);
        } elseif (!empty($this->dataJson["sequence"]["random"])) {
            $data = $this->random($this->dataJson["sequence"]["random"]);
            $this->saveJson($request->client, $this->dataJson["sequence"]["random"]);
        } elseif (!empty($this->dataJson["sequence"]["time_condition"])) {
            $hour = date('A', time());
            if ($hour == "AM") {
                $th = $this->dataJson["sequence"]["time_condition"][0];
            } else {
                $th = $this->dataJson["sequence"]["time_condition"][1];
            }
            $data = $this->data($th['sequence'][0]);
            unset($th['sequence'][0]);
            $this->saveJson($request->client, $th['sequence']);
        } else {
            if (count($this->dataJson['sequence']) > 1) {
                if (!empty($this->dataJson['sequence'][0]['random'])) {
                    $data = $this->random($this->dataJson['sequence'][0]['random']);
                    unset($this->dataJson['sequence'][0]);
                    $array = [];
                    foreach ($this->dataJson['sequence'] as $key => $value) {
                        $array[] = $value;
                    }
                    $this->saveJson($request->client, $array);
                } else {
                    $data = $this->data($this->dataJson['sequence'][0]);
                    unset($this->dataJson['sequence'][0]);
                    $array = [];
                    foreach ($this->dataJson['sequence'] as $key => $value) {
                        $array[] = $value;
                    }
                    $this->saveJson($request->client, $array);
                }
            } else {
                $id = array_keys($this->dataJson['sequence'][0])[0];
                $data = $this->data($id);
                $this->saveJson($request->client, $this->dataJson['sequence'][0][$id]);
            }
        }

        return response()->json(['success' => $data, 'user_id' => $user->id], $this->successStatus);
    }

    public function sequence($client, $params) {
        if (!empty($params[0]['random'])) {
            $data = $this->random_process($client, $params[0]['random']);
            unset($params[0]);
            $array = [];
            foreach ($params as $value) {
                $array[] = $value;
            }
            $this->saveJson($client, $array);
            return $data;
        } elseif (!empty($params["random"])) {
            $this->random_process($client, $params['random']);
        } elseif (!empty($params['time_condition'])) {
            $this->time_condition_process($client, $params['time_condition']);
        } else {
            if (is_string($params[0])) {
                $id = $params[0];
                $data = $this->data($id);
                unset($params[0]);
                $array = [];
                foreach ($params as $value) {
                    $array[] = $value;
                }
                $this->saveJson($client, $array);
                return $data;
            } else {
                $id = array_keys($params[0])[0];
                $data = $this->data($id);
                $this->saveJson($client, $params[0][$id]);
                return $data;
            }
        }
    }

    public function time_condition_process($client, $params) {
        $hour = date('A', time());
        if ($hour == "AM") {
            $th = $params[0];
        } else {
            $th = $params[1];
        }
        if ($th['sequence'][0] == "end") {
            return 'end';
        }
        $data = $this->data($th['sequence'][0]);
        unset($th['sequence'][0]);
        $array = [];
        foreach ($th['sequence'] as $key => $value) {
            $array[] = $value;
        }
        $this->saveJson($client, $array);
        return $data;
    }

    public function random_process($client, $params) {
        $data_session = unserialize(User::where('name', $client)->first()->data_json);
        if (!empty($params['random'])) {
            $ids = [];
            $option = [];
            foreach ($params['random'] as $key => $value) {
                if (!empty($value['option'])) {
                    $ids[] = $key;
                } else {
                    if (is_string($value)) {
                        $ids[] = $value;
                    } else {
                        $ids[] = array_keys($value)[0];
                        $option[array_keys($value)[0]] = [array_keys($value)[0] => $value[array_keys($value)[0]]];
                    }
                }
            }

            $data = $this->random($ids);

            if (!empty($option[$data['question']['id']])) {
                $this->saveJson($client, $option[$data['question']['id']]);
            } else {
                if (count($data_session) == 1) {
                    if (!empty($data_session[0])) {
                        $this->saveJson($client, $data_session[0]);
                    } else {
                        $this->saveJson($client, $data_session);
                    }
                } else {
                    unset($data_session[0]);
                    $array = [];
                    foreach ($data_session as $value) {
                        $array[] = $value;
                    }
                    $this->saveJson($client, $array);
                }
            }
            return $data;
        } else {

//                unset($params[0]);
//                $array = [];
//                foreach ($params as $value) {
//                    $array[] = $value;
//                }
//                dd($array);
//                session()->put('data', $array);


            $data = $this->random($params);
            return $data;
//            }
        }
    }

    public function save(Request $request) {
        $user = User::where('name', $request->get('client'))->first();
        $question = Question::where('id', $request->question)->first();
        $request['question_id'] = $question->id;
        $request['question'] = $question->title;
        $request['group'] = $question->group;
        $answer = Answer::where('id', $request->answer)->first();
        $rsw = $request->answer;
        if ($answer) {
            $request['answer'] = $answer->title;
        } else {
            $request['answer'] = $request->answer;
        }
        $request['user_id'] = $user->id;
        UserAnswer::create($request->all());
        $data_session = unserialize($user->data_json);
        if (!empty($data_session['option'][$rsw]["sequence"])) {
            if ($question->group == 1) {
                User::where('name', $request->get('client'))->update([
                    'history' => serialize($data_session['option'][$rsw]),
                ]);
            }
            $data = $this->sequence($request->client, $data_session['option'][$rsw]["sequence"]);
            return response()->json(['success' => $data, $this->successStatus]);
        }
        if (!empty($data_session[$question->id]['option'][$rsw])) {
            if (!empty($data_session[$question->id]['option'][$rsw]['sequence']) && $data_session[$question->id]['option'][$rsw]['sequence'][0] == "end") {
                return response()->json(['success' => 'end', $this->successStatus]);
            }
            $data = $this->sequence($request->client, $data_session[$question->id]['option'][$rsw]["sequence"]);
            return response()->json(['success' => $data, $this->successStatus]);
        }
        if (!empty($data_session['random'][$question->id][$rsw])) {
            $data = $this->sequence($request->client, $data_session['random'][$question->id]['option'][$rsw]["sequence"]);
            return response()->json(['success' => $data, $this->successStatus]);
        }

        if (!empty($data_session[0]['random'])) {
            $data = $this->random_process($request->client, $data_session[0]);
            return response()->json(['success' => $data, $this->successStatus]);
        }
        if (!empty($data_session['random'])) {
            $data = $this->random_process($request->client, $data_session);
            return response()->json(['success' => $data, $this->successStatus]);
        }
        if (!empty($data_session['time_condition'])) {
            $data = $this->time_condition_process($request->client, $data_session['time_condition']);
            return response()->json(['success' => $data, $this->successStatus]);
        }
        if (!empty($data_session[0])) {
            if ($data_session[0] == "end") {
                $this->saveJson($request->client, 'end');
                return response()->json(['success' => 'end', $this->successStatus]);
            } elseif (!empty(current($data_session))) {
                if (count($data_session) == 1) {
                    $data = $this->time_condition_process($request->client, $data_session[0]['time_condition']);
                } else {
                    $data = $this->data($data_session[0]);
                    unset($data_session[0]);
                    $array = [];
                    foreach ($data_session as $key => $value) {
                        $array[] = $value;
                    }
                    $this->saveJson($request->client, $array);
                }
                return response()->json(['success' => $data, $this->successStatus]);
            } else {
                $this->data($data_session[0]);
                unset($data_session[0]);
                $array = [];
                foreach ($data_session as $key => $value) {
                    $array[] = $value;
                }
                if (!empty(current($data_session))) {
                    $data = $this->random(current($data_session));
                    session()->put('data', $array);
                    $this->saveJson($request->client, $array);
                }
                $this->saveJson($request->client, $array);

                return response()->json(['success' => $array, $this->successStatus]);
            }
        }
        if (!empty($data_session[1]) && $data_session[1] == "end") {
            $this->saveJson($request->client, 'end');
            return response()->json(['success' => 'end', $this->successStatus]);
        }
    }

    private function saveJson($client, $data) {
        User::where('name', $client)->update([
            'data_json' => serialize($data)
        ]);
    }

    public function data($id) {
        $ids = $this->checkQuestion();
        $question = Question::where('id', $id)->first();
        if (in_array($id, $ids)) {
            $question = Question::whereNotIn('id', $ids)->first();
        }

        $data = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
                'type' => (int) $question->type,
                'answers' => $question->answers,
                'group' => $question->group
            ]
        ];
        return $data;
    }

    public function random($ids) {
        $array_ids = $this->checkQuestion();
        $array = [];
        foreach ($ids as $key => $value) {
            if (!in_array($value, $array_ids)) {
                $array[] = $value;
            }
        }
        if (!empty($array)) {
            $k = array_rand($array);
            $question = Question::where('id', $array[$k])->first();
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
    }

    public function checkQuestion() {
        $user_answer_1 = UserAnswer::select('question_id')
                ->join('questions', 'questions.id', '=', 'user_answers.question_id')
                ->where('user_answers.user_id', session()->get('user_id'))
                ->where('questions.group', 1)
                ->get()
                ->toArray();
        $user_answer_3 = UserAnswer::select('question_id')
                ->join('questions', 'questions.id', '=', 'user_answers.question_id')
                ->where('user_answers.user_id', session()->get('user_id'))
                ->whereMonth('user_answers.created_at', '=', date('m'))
                ->whereYear('user_answers.created_at', '=', date('Y'))
                ->where('questions.group', 3)
                ->get()
                ->toArray();
        $array_1 = $array_2 = [];
        if ($user_answer_1) {
            foreach ($user_answer_1 as $key => $value) {
                $array_1[] = (string) $value['question_id'];
            }
        }
        if ($user_answer_3) {
            foreach ($user_answer_3 as $key => $value) {
                $array_2[] = (string) $value['question_id'];
            }
        }
        $array = array_merge($array_1, $array_2);
        return $array;
    }

    public function toArrayQuestion($data, $id) {
        if ($data) {
            foreach ($data as $key => $value) {
                if ($value != $id) {
                    $array[] = $value;
                }
            }
            return $array;
        }
        return FALSE;
    }

}
