<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedDomain extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public $timestamps = false;

    public function form(){
        return $this->belongsTo(User::class, 'form_id','id');
    }

}
