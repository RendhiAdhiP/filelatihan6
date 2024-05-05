<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllowedDomain;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{

    public function create(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:forms,slug|regex:/^[a-zA-Z.-]+$/',
            'allowed_domains' => 'array',
        ]);

        $form = Form::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description ?? null,
            'limit_one_response' => $request->limit_one_response ?? 1,
            'creator_id' => $request->user()->id,
        ]);

        if ($request->allowed_domains != null) {
            foreach ($request->allowed_domains as $a) {
                AllowedDomain::create([
                    'form_id' => $form->id,
                    'domain' => $a,
                ]);
            }
        }

        $i = [
            'id' => $form->id,
            'name' => $form->name,
            'slug' => $form->slug,
            'allowed_domains' => $form->allowedDomain->map(function ($a) {
                return $a->pluck('domain');
            }),
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
        ];

        return response()->json(['message' => 'Create form success', 'form' => $i], 200);
    }

    public function all(Request $request)
    {
        $user = $request->user()->id;
        $form = Form::where('creator_id', $user)->get();

        return response()->json(['message' => 'Get all form success', 'form' => $form], 200);
    }

    public function detail($slug, Request $request)
    {
        $user = $request->user()->id;
        $email = $request->user()->email;
        $form = Form::where('slug', $slug)->with('allowedDomain','question')->first();

        $domainn = explode('@', $email);
        $domain = $domainn[1];

        $dom = $form->allowedDomain->map(function ($d) use ($domain) {
            $dmn = $d->where('domain', $domain)->first('domain');
            return $dmn;

        });

        if ($dom[0] == null ) {
            return response()->json(['messgae' => 'forbbiden access'], 403);
        }



        $i = [
            'id' => $form->id,
            'name' => $form->name,
            'slug' => $form->slug,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
            'creator_id' => $form->allowedDomain->map(function($d){
                    return $d->pluck('domain');
            }),
            'question'=>$form->question

        ];
      

        return response()->json(['message' => 'Get detail success','form'=>$i], 200);
    }
}

