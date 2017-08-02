<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_document_line extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'payment_document_line';

    public function getConcept()
    {
    	return $this->belongsTo('sigc\Concept','id_entity')->first();
    }

    public function getDiscount()
    {
    	return $this->belongsTo('sigc\Discount','id_entity')->first();
    }

    public function getInterest()
    {
        return $this->belongsTo('sigc\Interest','id_entity')->first();
    }
}
