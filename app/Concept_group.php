<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use sigc\conceptxstudent;
use sigc\Group;
use sigc\Student;
use sigc\studentXgroupXyear;
use sigc\Configuration;

class Concept_group extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'concept_group';

    public function Concepts()
    {
    	return $this->hasMany('sigc\Concept','id_concepto_grupo');
    }

    public function Discounts()
    {
		return $this->belongsToMany('sigc\Discount', 'concept_groupsxdiscount', 'id_concept_groups', 'id_discount');
    }

    public function get_discounts_id()
    {
        return $this->belongsToMany('sigc\Discount', 'concept_groupsxdiscount', 'id_concept_groups', 'id_discount')->select('id_discount');
    }

    public function set_discounts_id($discounts){
        //First add the relationship
        $this->Discounts()->detach();
        $dataSet = [];
        if (!is_null($discounts)) {
            foreach ($discounts as $discountId) {
                $dataSet[] = [
                    'id_concept_groups'  => $this->id,
                    'id_discount'    => $discountId
                ];
            }

            DB::table('concept_groupsxdiscount')->insert($dataSet);
        }
        
        //Second update the amount for each group, for each student

    }

    public function Interests()
    {
		return $this->belongsToMany('sigc\Interest', 'concept_groupsxinterest', 'id_concept_groups', 'id_interest');
    }

    public function get_interests_id()
    {
        return $this->belongsToMany('sigc\Interest', 'concept_groupsxinterest', 'id_concept_groups', 'id_interest')->select('id_interest');
    }

    public function set_interests_id($interests){
        $this->Interests()->detach();
        $dataSet = [];
        if (!is_null($interests)) {
            foreach ($interests as $interestId) {
                $dataSet[] = [
                    'id_concept_groups'  => $this->id,
                    'id_interest'    => $interestId
                ];
            }
            DB::table('concept_groupsxinterest')->insert($dataSet);
        }
    }
}
