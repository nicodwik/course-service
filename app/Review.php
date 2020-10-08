<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'course_id', 'user_id', 'rating', 'note'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
