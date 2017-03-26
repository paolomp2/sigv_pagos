<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'schedules';

    public function Concepts()
    {
		return $this->belongsToMany('sigc\Concept', 'schedulexconcept', 'id_schedule', 'id_concept');
    }
}
