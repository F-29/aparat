<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        $categories = [
            'عمومی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'خبری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'علم و تکنولوژی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'ورزشی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'بانوان' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'بازی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'طنز' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'آموزشی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'تفریحی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'فیلم' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'مذهبی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'موسیقی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'سیاسی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'حوادث' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'گردشگری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'حیوانات' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'متفرقه' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'تبلیغات' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'هنری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'کارتون' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'سلامت' => ['icon' => null, 'banner' => null, 'user_id' => null],

            // personal category
            'ساندویچ' => ['icon' => null, 'banner' => null, 'user_id' => 1],
        ];
        foreach ($categories as $categoryName => $options) {
            Category::create([
                'title' => $categoryName,
                'icon' => $options['icon'],
                'banner' => $options['banner'],
                'user_id' => $options['user_id'],
            ]);
            $this->command->info('added ' . $categoryName . ' category');
        }
    }
}
