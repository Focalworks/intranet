<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Eloquent::unguard();

        $this->call('VariableSeed');
        $this->command->info('Variables table seeded!');

        $this->call('AssessmentSeed');
        $this->command->info('Assessment table seeded!');
	}

}