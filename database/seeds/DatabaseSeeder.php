<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ArticlesAndCategoriesTableSeeder::class);
    }
}

/**
 * 
 */
class UsersTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table("users")->truncate();

		User::create([
			"name" => "Ibra Njeru",
			"email" => "email@sample.com",
			"password" => bcrypt("123456")
		]);

		$faker = Faker\Factory::create();

		foreach (range(1, 10) as $i) {
			User::create([
				"name" => $faker->name,
				"email" => $faker->email,
				"password" => bcrypt("123456")
			]);
		}
	}
}

class ArticlesAndCategoriesTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table("categories")->truncate();
		\DB::table("articles")->truncate();

		$faker = Faker\Factory::create();
		$user_ids = User::pluck("id")->toArray();
		$cat_names = ["Finance", "Politics", "Sports"];

		foreach (range(1, 10) as $i) {
			$cat = \App\Category::create([
				"user_id" => $faker->randomElement($user_ids),
				"name" => $faker->randomElement($cat_names)
			]);

			foreach (range(1, 10) as $i) {
				\App\Article::create([
					"user_id" => $faker->randomElement($user_ids),
					"category_id" => $cat->id,
					"title" => $faker->realText(20),
					"description" => $faker->realText(2500)
				]);
			}
		}
	}
}
