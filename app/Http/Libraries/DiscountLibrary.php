<?php

namespace sigc\Http\Libraries;

class DiscountLibrary{
	
	private $config;

	function __construct(){
		$this->config = Configuration::where('current',1)->first();
	}

	public updateGroup(Int $GroupId, bool $is_adding)
	{

	}

	public updateAllGroups(){
		
	}
	/**
	*@return A list of discount group by concepts
	*
	*/
	public static function getAllDiscountsByConcepts($array_concepts_id)
	{

	}

	private static function getDiscountsByConcept($ConceptId){
		$query = "select
					d.name, d.id, d.amount, d.percentage_flag, DATE_ADD(c.fecha_vencimiento, INTERVAL d.days_after_expiration_date DAY) AS expiration_date
				from
					discounts d, concepts c, conceptxdiscount cxd
				where
					d.id = cxd.discount_id and
					c.id = cxd.concept_id and
					d.year = c.year and
					expiration_date <= now()
					and c.id = 1
            ";
        return DB::select(DB::raw($query));
	}
}
?>