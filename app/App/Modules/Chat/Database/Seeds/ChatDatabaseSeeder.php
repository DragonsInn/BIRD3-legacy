<?php
namespace BIRD3\App\Modules\Chat\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ChatDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('BIRD3\App\Modules\Chat\Database\Seeds\FoobarTableSeeder');
	}

}
