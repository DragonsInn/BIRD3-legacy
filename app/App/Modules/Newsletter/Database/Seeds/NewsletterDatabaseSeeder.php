<?php
namespace BIRD3\App\Modules\Newsletter\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NewsletterDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('BIRD3\App\Modules\Newsletter\Database\Seeds\FoobarTableSeeder');
	}

}
