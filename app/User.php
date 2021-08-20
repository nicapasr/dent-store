<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const ADMIN_TYPE = 1;
    const BOARD_TYPE = 2;
    const DEFAULT_TYPE = 3;

    protected $table = 'user_profiles';
    protected $fillable = ['username', 'password', 'permission', 'department', 'phone', 'first_name', 'last_name', 'line_token', 'remember_token'];
    public $timestamps = false;

    protected $hidden = [
        'id', 'password', 'remember_token'
    ];

    public function isAdmin()
    {
        return $this->permission === self::ADMIN_TYPE;
    }

    public function getDepartment()
    {
        return $this->belongsTo('App\DepartmentModel', 'department', 'id_dep');
    }

    public function getPermission()
    {
        return $this->belongsTo('App\PermissionModel', 'permission', 'id_user_permission');
    }
}
