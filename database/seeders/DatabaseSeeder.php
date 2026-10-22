<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Sample quotes grouped by the name of the user who shares them.
     *
     * @var array<string, list<array{0: string, 1: string}>>
     */
    private const SAMPLE_QUOTES = [
        'Lujain' => [
            ['The best way to predict the future is to invent it.', 'motivation'],
            ['Small steps every day add up to big results.', 'success'],
        ],
        'Sara' => [
            ['Simplicity is the soul of efficiency.', 'wisdom'],
            ['Be kind, for everyone you meet is fighting a hard battle.', 'life'],
        ],
        'Zahra' => [
            ['Good design is invisible until it is missing.', 'wisdom'],
            ['I told my code a joke. It did not compile with laughter.', 'humor'],
        ],
        'Maha' => [
            ['Where there is love there is life.', 'love'],
            ['Fall seven times, stand up eight.', 'motivation'],
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $categories = Category::pluck('id', 'slug');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach (self::SAMPLE_QUOTES as $name => $quotes) {
            $user = User::factory()->create(['name' => $name]);

            foreach ($quotes as [$content, $categorySlug]) {
                $user->quotes()->create([
                    'content' => $content,
                    'category_id' => $categories[$categorySlug],
                ]);
            }
        }

        $users = User::all();

        foreach (Quote::all() as $quote) {
            $quote->likers()->attach($users->random(fake()->numberBetween(0, $users->count()))->pluck('id'));
        }
    }
}
