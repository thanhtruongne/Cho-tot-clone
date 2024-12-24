<?php

namespace Database\Seeders;

// use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Cron;
class CronDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $crons = Cron::getCommand();
        foreach($crons as $item){
            $split = explode(' ',$item->expression);
            $data[] = [
                'code' => $item->code,
                'description' => $item->name,
                'command' => $item->code,
                'minute' => $split[0],
                'hour' => $split[1],
                'day' => $split[2],
                'month' => $split[3],
                'day_of_week' =>$split[4],
                'expression' => $item->expression,
                'enabled' => 1,
            ];
        }
        \DB::table('cron')->truncate();
        \DB::table('cron')->insert($data);
        
    }
}