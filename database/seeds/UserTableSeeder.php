<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = factory(User::class)->make([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin',
            'email' => 'm.chegeni797979@gmail.com',
            'mobile' => '+989901633417'
        ]);
        $user->save();
        $this->command->warn('Admin User Created');
    }

    private function createUser()
    {
        $user = factory(User::class)->make([
            'type' => User::TYPE_USER,
            'name' => 'ali',
            'email' => 'ali@gmail.com',
            'mobile' => '+989166636710'
        ]);
        $user->save();
        $this->command->warn('User Created');
    }
}
