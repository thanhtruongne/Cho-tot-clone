<?php
namespace Modules\Permission\Entities;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
   use Cachable;
   protected $table = 'roles';
   protected $table_name = "Vai trò";
   protected $fillable = [
       'code',
       'name',
       'type',
       'guard_name',
       'description',
       'created_by',
       'updated_by',
       'status'
   ];
   protected $primaryKey = 'id';
}