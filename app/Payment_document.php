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

    public function Lines()
    {
    	return $this->hasMany('sigc\Payment_document_line', 'id_document_payment')->get();
    }

}
