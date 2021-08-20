<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    //
    protected $table = 'department';
    protected $fillable = ['id_dep', 'dep_name'];
    protected $primaryKey = 'id_dep';
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany('App\User');
    }
}
