<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $fillable = ['prefix', 'fname', 'lname'];
    public $timestamps = false;
}
