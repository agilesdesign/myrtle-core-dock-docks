<?php

use Illuminate\Database\Seeder;

class DocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$docks = Myrtle\Core\Docks\Facades\Docks::all();

		$docks->each(function ($dock, $name) {
			$dock->storeOptions();
		});
    }
}
