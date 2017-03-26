<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class conceptxstudent extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'conceptxstudent';

    public function student(){
    	return $this->belongsTo('sigc\Student', 'id_student');
    }

    public function Concept(){
    	return $this->belongsTo('sigc\Concept', 'id_concept');
    }

}
