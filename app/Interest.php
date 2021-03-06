<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'interests';

    public function Concepts_groups()
    {
        return $this->belongsTo('sigc\Concept_group', 'concept_group');
    }

    public function Concepts()
    {
		return $this->belongsToMany('sigc\Concept', 'conceptxdiscount', 'id_interest', 'id_concept');
    }
}