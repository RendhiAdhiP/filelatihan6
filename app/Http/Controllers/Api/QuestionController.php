<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function add($slug, Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes ',
            'choices' => 'required_if:choice_type,multiple choice,dropdown,multiple choice,checkboxes|array',
        ]);


        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
        $user = $request->user()->id;

        if ($form->creator_id != $user) {
            return response()->json(['messgae' => 'For bidden access'], 403);
        }

        if ($form == null) {
            return response()->json(['messgae' => 'Form Not Found'], 404);
        }

        if (isset($validate['choices'])) {
            $validate['choices'] = trim(json_encode($validate['choices']), '[],"');
        }

        $question = Question::create([
            "name" => $validate['name'],
            "type" => $validate['type'],
            "is_required" => $validate['is_required'] ?? true,
            "choices" => $validate['choices'] ?? null,
            "form_id" => $form->id,
        ]);

        $i = [
            'name' => $question->name,
            'type' => $question->type,
            'is_required' => $question->is_required,
            'choices' => $question->choices,
            'form_id' => $question->form_id,
            'id' => $question->id,
        ];

        return response()->json(['message' => 'Add question success', 'question' => $i], 200);
    }

    public function delete($slug,$id, Request $request)
    {
        
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
        $user = $request->user()->id;
        $question = Question::where('id',$id)->first();

        if ($form->creator_id != $user) {
            return response()->json(['messgae' => 'For bidden access'], 403);
        }

        if ($form == null) {
            return response()->json(['messgae' => 'Form Not Found'], 404);
        }

        if($question == null ){
            return response()->json(['messgae' => 'Question not Found'], 404);

        }

        $question->delete();

        return response()->json(['messgae' => 'Remove question success'], 200);


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
