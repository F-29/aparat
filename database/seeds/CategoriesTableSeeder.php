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
            'عمومی' => ['icon' => '', 'banner' => ''],
            'خبری' => ['icon' => '', 'banner' => ''],
            'علم و تکنولوژی' => ['icon' => '', 'banner' => ''],
            'ورزشی' => ['icon' => '', 'banner' => ''],
            'بانوان' => ['icon' => '', 'banner' => ''],
            'بازی' => ['icon' => '', 'banner' => ''],
            'طنز' => ['icon' => '', 'banner' => ''],
            'آموزشی' => ['icon' => '', 'banner' => ''],
            'تفریحی' => ['icon' => '', 'banner' => ''],
            'فیلم' => ['icon' => '', 'banner' => ''],
            'مذهبی' => ['icon' => '', 'banner' => ''],
            'موسیقی' => ['icon' => '', 'banner' => ''],
            'سیاسی' => ['icon' => '', 'banner' => ''],
            'حوادث' => ['icon' => '', 'banner' => ''],
            'گردشگری' => ['icon' => '', 'banner' => ''],
            'حیوانات' => ['icon' => '', 'banner' => ''],
            'متفرقه' => ['icon' => '', 'banner' => ''],
            'تبلیغات' => ['icon' => '', 'banner' => ''],
            'هنری' => ['icon' => '', 'banner' => ''],
            'کارتون' => ['icon' => '', 'banner' => ''],
            'سلامت' => ['icon' => '', 'banner' => ''],
        ];
        foreach ($categories as $categoryName => $options) {
            Category::create([
                'title' => $categoryName,
                'icon' => $options['icon'],
                'banner' => $options['banner']
            ]);
            $this->command->info('added ' . $categoryName . ' category');
        }
    }
}
