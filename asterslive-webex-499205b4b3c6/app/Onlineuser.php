<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Onlineuser extends Model
{
    protected $table = 'onlineuser';
    
    protected $fillable = array('metting_id','metting_user_id', 'name', 'email');
}
