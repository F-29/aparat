<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

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
        $this->call(TagTableSeeder::class);
        $this->call(PlaylistTableSeeder::class);
        Schema::enableForeignKeyConstraints();

        Artisan::call('aparat:clear');
        $this->command->info('Clear all temporary files(videos, category banners, channel banners)');
    }
}
