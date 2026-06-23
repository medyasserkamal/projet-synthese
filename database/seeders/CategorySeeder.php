<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technologie', 'slug' => 'technologie'],
            ['name' => 'Design UI/UX', 'slug' => 'design-ui-ux'],
            ['name' => 'Développement', 'slug' => 'developpement'],
            ['name' => 'Intelligence Artificielle', 'slug' => 'intelligence-artificielle'],
            ['name' => 'Style de vie', 'slug' => 'style-de-vie'],
            ['name' => 'Productivité', 'slug' => 'productivite'],
            ['name' => 'Santé & Bien-être', 'slug' => 'sante-bien-etre'],
            ['name' => 'Business', 'slug' => 'business'],
        ];

        foreach ($categories as $category) {
            ArticleCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
