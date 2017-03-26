<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class studentXfamily_member extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = 'studentxfamily_member';

    public function Student()
    {
        return $this->belongsTo('sigc\Student','id_student');
    }

    public function Relationship()
    {
        return $this->belongsTo('sigc\Relationship','id_relationship');
    }

    public function Family_member()
    {
        return $this->belongsTo('sigc\Family_member','id_family_member');
    }
}
