<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Group extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'groups';

    public function Concepts()
    {
		return $this->belongsToMany('sigc\Concept', 'conceptxgroup', 'id_group', 'id_concept');
    }

    public function Students()
    {
		return $this->belongsToMany('sigc\Student', 'studentxgroupxyear', 'id_group', 'id_student');
    }

    public function conceptXgroup()
    {
        return $this->hasMany('sigc\conceptXgroup', 'id_group');
    }

    public function discountxgroup($value='')
    {
        return $this->hasMany('sigc\discountXgroup', 'id_group');
    }

    public function interestxgroup($value='')
    {
        return $this->hasMany('sigc\interestxgroup', 'id_group');
    }

    public function studentxgroupxyear()
    {
        return $this->hasMany('sigc\studentxgroupxyear', 'id_group');
    }
    /**
    *   @return a list of Id groups filtered by all related sub groups
    */
    public function allFiltered()
    {
        
    }
    
}
