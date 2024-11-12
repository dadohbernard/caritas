<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class UserRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'role_name',
        'status',
        'created_at',
        'updated_at',
    ];
    public static function all($columns = ['*'])
    {
        return static::query()->orderby('role_name', 'asc')->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
}
