<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class studentXgroupXyear extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = 'studentxgroupxyear';

    public function Classroom()
    {
        return $this->belongsToMany('sigc\Classroom','studentxclassromxyear','id_student','id_classroom');
    }
}
