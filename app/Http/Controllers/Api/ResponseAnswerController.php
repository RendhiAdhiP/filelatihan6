<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Answer;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResponseAnswerController extends Controller
{
    public function response($slug, Request $request)
    {
        $validate = $request->validate([
            'answer' => 'array|required',
            'answer.*.question_id' => 'required_with:answer.*',
        ]);

        $email = $request->user()->email;
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();

        $domainn = explode('@', $email);
        $domain = $domainn[1];

        $dom = $form->allowedDomain->map(function ($d) use ($domain) {
            $dmn = $d->where('domain', $domain)->first('domain');
            return $dmn;
        });

        if ($dom[0] == null) {
            return response()->json(['messgae' => 'forbbiden access'], 403);
        }

        $user = $request->user();

        $response = $user->response()->attach($form->id, ['date' => now()]);
        $res = Response::where('user_id',$request->user()->id)->first();

        for ($i = 0; $i < count($request->answer); $i++) {
            $answer = new Answer;
            $answer->question_id =  $request->answer[$i]['question_id'];
            $answer->value =  $request->answer[$i]['value'];
            $answer->response_id = $res->id ;
            $answer->save();
        }

        $a = [
            'question_id'=>$res->answer,
            'value',
        ];

        dd($request->all());


        return response()->json(['message' => 'Add question success','answer'=>$a ], 200);

    }
}


// public function login(Request $request){
//     $validate = $request->validate([
//         'data'=>'required',
//         'data'=>'required',
//         'data'=>'required',
//         'data'=>'required',
//         'data'=>'required',
//     ]);
// }



// $i = [
//     'data' => 'required',
//     'data' => 'required',
//     'data' => 'required',
//     'data' => 'required',
// ];