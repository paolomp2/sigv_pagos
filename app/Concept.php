<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'concepts';

    public function Concept_group()
    {
    	return $this->belongsTo('sigc\Concept_group','id_concept_group');
    }

    public function Discounts()
    {
		return $this->belongsToMany('sigc\Discount', 'conceptxdiscount', 'id_concept', 'id_discount');
    }

    public function Interests()
    {
		return $this->belongsToMany('sigc\Interest', 'conceptxinterest', 'id_concept', 'id_interest');
    }

    public function Groups()
    {
        return $this->belongsToMany('sigc\Group', 'conceptxgroup', 'id_concept', 'id_group')->withPivot('deleted_at')->withTimestamps();
    }
}
