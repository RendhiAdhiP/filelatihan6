<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public $timestamps = false;

 
    public function answer(){
        return $this->belongsTo(Answer::class, 'response_id','id');
    }
        // public function answer(){
        //     return $this->belongsToMany(Question::class, 'answers','response_id','qusetion_id')->withPivot(['date']);
    // }
}
