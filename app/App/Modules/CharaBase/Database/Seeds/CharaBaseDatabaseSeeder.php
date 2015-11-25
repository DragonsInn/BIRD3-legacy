<?php
namespace BIRD3\App\Modules\CharaBase\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CharaBaseDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('BIRD3\App\Modules\CharaBase\Database\Seeds\FoobarTableSeeder');
	}

}
