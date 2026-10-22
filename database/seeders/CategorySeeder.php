<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * The categories every quote can belong to.
     *
     * @var list<string>
     */
    public const NAMES = ['Motivation', 'Wisdom', 'Life', 'Love', 'Success', 'Humor'];

    /**
     * Seed the quote categories.
     */
    public function run(): void
    {
        foreach (self::NAMES as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
