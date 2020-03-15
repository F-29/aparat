<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(UserTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
