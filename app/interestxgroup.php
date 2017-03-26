<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class interestXgroup extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'interestxgroup';

    public function groups(){
    	return $this->belongsTo('sigc\Group', 'id_group');
    }

}
