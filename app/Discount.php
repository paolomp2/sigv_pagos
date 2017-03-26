<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'discounts';

    public function Concepts_groups()
    {
    	return $this->belongsTo('sigc\Concept_group', 'id_concept_group');
    }

    public function Concepts()
    {
		return $this->belongsToMany('sigc\Concept', 'conceptxdiscount', 'id_discount', 'id_concept');
    }
}
