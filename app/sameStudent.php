<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sameStudent extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'samestudents';
}
