<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class conceptxinterest extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'conceptxinterest';
}
