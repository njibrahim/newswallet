<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
    	return $this->belongsTo("App\User");
    }

    public function category()
    {
    	return $this->belongsTo("App\Category");
    }

    public function views()
    {
        return $this->hasMany("App\View");
    }

    public function getViews($auth = false)
    {
    	if ($auth) {
            return $this->views()
                ->where("user_id", auth()->id())
    			->count();

    	} else {
    		return $this->views->count();
    	}
    }
}
