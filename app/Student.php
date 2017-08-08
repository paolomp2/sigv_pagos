<?php

namespace sigc;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'students';

    public function Classroom()
    {
        return $this->belongsTo('sigc\Group','identifier','identifier');
    }

    public function Groups()
    {
        return $this->belongsToMany('sigc\Group','studentXgroupXyear','id_student','id_group')->withPivot('year');
    }

    public function Family_member()
    {
        return $this->belongsToMany('sigc\Family_member','studentxfamily_member','id_family_member','id_student')->withTimestamps();
    }

    public function studentxgroupxyear()
    {
        return $this->hasMany('sigc\studentXgroupXyear', 'id_student');
    }

    
}
