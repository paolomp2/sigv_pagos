<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class payment_document_group extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'payment_document_group';
}
