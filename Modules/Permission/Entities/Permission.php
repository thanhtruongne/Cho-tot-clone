<?php
namespace Modules\Permission\Entities;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
   use Cachable;
    protected $table = 'permissions';
    protected $table_name = "Danh sách quyền";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'guard_name',
        'model',
        'parent',
        'extend',
        'group',
    ];


    public static function isAdmin() {
        $cacheKey = 'admin_access_for_' . Auth::user()->id;
        return Cache::rememberForever($cacheKey, function () {
            if (in_array(Auth::user()->username, ['admin', 'superadmin'])) {
                return true;
            }
            return Auth::user() ? Auth::user()->roles()->where('name', 'Admin')->count() : false;
        });
    }
}