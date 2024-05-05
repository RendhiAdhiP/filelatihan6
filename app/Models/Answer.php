<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function response(){
        return $this->hasMany(Response::class);
    }
    public function question(){
        return $this->hasMany(Question::class);
    }
}
