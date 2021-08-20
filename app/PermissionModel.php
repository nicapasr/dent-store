<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    //
    protected $table = 'user_permission';
    protected $fillable = ['permission_name'];
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany('App\User');
    }
}
