<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;    
    public $guarded = [];
    
    public $timestamps = false;

    public function form(){
        return $this->belongsTo(Form::class, 'form_id','id');
    }

    // public function answer(){
    //     return $this->belongsToMany(Response::class, 'answers','question_id','response_id')->withPivot(['date']);
    // }
    
    public function answer(){
        return $this->belongsTo(Answer::class, 'question_id','id');
    }
}
