<?php

namespace App\Models;

use Composer\ClassMapGenerator\ClassMapGenerator;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    use Cachable;
    protected $table = 'cron';
    protected $fillable = [
        'code',
        'command',
        'description',
        'minute',
        'hour',
        'day',
        'month',
        'day_of_week',
        'last_run',
        'expression',
        'enabled'
    ];
    

    public static function getCommand(){
       //command app
       $class = ClassMapGenerator::createMap(app_path('Console/Commands/'));
       foreach ($class as $index => $item) {
           $_class = new $index();
           $pros = new \ReflectionProperty($_class,'signature');
           $pros->setAccessible(true);
           $code =$pros->getValue($_class);

           $pros = new \ReflectionProperty($index,'description');
           $pros->setAccessible(true);
           $name = $pros->getValue($_class);

           $obj = new \ReflectionClass($_class);
           if ($obj->hasProperty('expression')) {
               $pros = new \ReflectionProperty($index, 'expression');
               $pros->setAccessible(true);
               $expression = $pros->getValue($_class);
           }else{
               $expression = '* 1 * * *';
           }

           $pros = new \ReflectionProperty($index,'hidden');
           $pros->setAccessible(true);
           $hidden =$pros->getValue($_class);
           if (!$hidden)
               $commands_arr[] =(object)['code'=>$code,'name'=>$name,'expression'=>$expression];
       }
       return collect($commands_arr);
    }
}
