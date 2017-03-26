<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bulck extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'bulcks_excel';

    public function Students()
    {
    	return $this->hasMany('sigc\Student','id_bulcks_excel');
    }
}
