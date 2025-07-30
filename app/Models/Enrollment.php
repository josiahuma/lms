<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    //
    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


}
