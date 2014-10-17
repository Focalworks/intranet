<?php
/**
 * Created by PhpStorm.
 * User: Vijay-pc
 * Date: 10/17/2014
 * Time: 9:52 AM
 */

class VariableSeed extends Seeder {

    public function run()
    {
        $insert=array('name'=>'cron_key','value'=>'abc123');
        DB::table('variables')->insert($insert);
    }

} 