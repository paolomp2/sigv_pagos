<?php

namespace sigc;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Family_member extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'family_members';

    public function Students()
    {
    	return $this->belongsToMany('sigc\Student','studentxfamily_member','id_student','id_family_member')->withTimestamps();
    }
}
