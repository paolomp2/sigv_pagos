<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'classrooms';

    public function Students()
    {
        return $this->belongsToMany('sigc\Student','studentxclassromxyear','id_classroom','id_student');
    }

}
