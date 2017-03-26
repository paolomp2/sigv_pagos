<?php

namespace sigc;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    public $timestamps = false;
    protected $table = 'relationships';
}
