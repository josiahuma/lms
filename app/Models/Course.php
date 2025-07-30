<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
    ];


    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class);
    }




}
