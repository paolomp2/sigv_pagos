<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_document extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'payment_document';

    public function Student()
    {
    	return $this->belongsTo('sigc\Student','id_student')->first();
    }

}
