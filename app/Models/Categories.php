<?php

namespace App\Models;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;


class Categories extends Model
{
    use NodeTrait;

    protected $table = 'categories';
    protected $table_name = "Danh mục sản phẩm";
    protected $primaryKey = 'id';
    protected $fillable = [
        'parent_id',
        '_lft',
        '_rgt',
        'icon',
        'name',
        'status',
        'type'
    ];
    public function getLftName()
    {
        return '_lft';
    }
    
    public function getRgtName()
    {
        return '_rgt';
    }
    
    public function getParentIdName()
    {
        return 'parent_id';
    }
    
    // Specify parent id attribute mutator
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }
}
