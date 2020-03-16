<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();
        $tags = [
            'عمومی',
            'خبری',
            'علم و تکنولوژی',
            'ورزشی',
            'بانوان',
            'بازی',
            'طنز',
            'آموزشی',
            'تفریحی',
            'فیلم',
            'مذهبی',
            'موسیقی',
            'سیاسی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'تبلیغات',
            'هنری',
            'کارتون',
            'سلامت',
        ];

        foreach ($tags as $tag) {
            Tag::create(['title' => $tag]);
        }
        $this->command->info("tags added: => " . "\n" . implode(" \n,", $tags));
    }
}
