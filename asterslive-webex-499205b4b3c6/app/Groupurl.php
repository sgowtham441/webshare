<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupurl extends Model
{
    protected $table = 'group_url';
    
    protected $fillable = array('user_id', 'metting_id', 'url','urlstatus');
}
