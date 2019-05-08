<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
