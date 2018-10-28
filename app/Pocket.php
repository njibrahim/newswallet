<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pocket extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
    	return $this->belongsTo("App\User");
    }

    public function article()
    {
    	return $this->belongsTo("App\Article");
    }
}
