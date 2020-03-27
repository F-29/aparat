<?php

use App\Playlist;
use Illuminate\Database\Seeder;

class PlaylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Playlist::truncate();

        for ($i = 1; $i < 13; $i++) {
            Playlist::create([
                'title' => 'play list number ' . $i,
                'user_id' => 2,
            ]);
            $this->command->info('playlists created: ' . "'play list number '" . $i);
        }
        Playlist::create([
            'title' => 'special play list!',
            'user_id' => 1,
        ]);
        $this->command->info('also, special play list created');
    }
}
