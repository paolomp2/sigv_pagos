<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class discountXgroup extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'discountxgroup';

    public function groups(){
    	return $this->belongsTo('sigc\Group', 'id_group');
    }

}
