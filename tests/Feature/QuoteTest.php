<?php

use App\Models\Category;
use App\Models\Quote;
use App\Models\User;

test('a guest sees every quote with its author and date but no edit or delete options', function () {
    $quote = Quote::factory()->for(User::factory()->state(['name' => 'Lujain']))->create();

    $this->get('/')
        ->assertOk()
        ->assertSee($quote->content)
        ->assertSee('Lujain')
        ->assertSee($quote->created_at->format('Y-m-d'))
        ->assertSee('Log in to share a quote')
        ->assertDontSee('Edit quote')
        ->assertDontSee('Delete quote');
});

test('a guest cannot share, edit or delete quotes', function () {
    $quote = Quote::factory()->create();

    $this->post('/quotes', ['content' => 'Keep going'])->assertRedirect(route('login'));
    $this->put("/quotes/{$quote->id}", ['content' => 'Changed'])->assertRedirect(route('login'));
    $this->delete("/quotes/{$quote->id}")->assertRedirect(route('login'));

    expect($quote->fresh()->content)->not->toBe('Changed');
});

test('a user can share a quote in a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user)->post('/quotes', ['content' => 'Small steps every day.', 'category_id' => $category->id])
        ->assertRedirect(route('home'));

    $quote = $user->quotes()->sole();
    expect($quote->content)->toBe('Small steps every day.');
    expect($quote->category->is($category))->toBeTrue();
});

test('a quote must have an existing category', function (?int $categoryId) {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/quotes', ['content' => 'Small steps every day.', 'category_id' => $categoryId])
        ->assertSessionHasErrors('category_id');

    expect(Quote::count())->toBe(0);
})->with([
    'missing' => [null],
    'does not exist' => [999],
]);

test('a quote must be between 3 and 155 characters', function (string $content) {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/quotes', ['content' => $content, 'category_id' => Category::factory()->create()->id])
        ->assertSessionHasErrors('content');

    expect(Quote::count())->toBe(0);
})->with([
    'too short' => ['Hi'],
    'too long' => [str_repeat('a', 156)],
]);

test('a user can edit their own quote and its category', function () {
    $quote = Quote::factory()->create();
    $newCategory = Category::factory()->create();

    $this->actingAs($quote->user)
        ->from('/?edit='.$quote->id)
        ->put("/quotes/{$quote->id}", ['content' => 'Fixed my typo', 'category_id' => $newCategory->id])
        ->assertRedirect(route('home'));

    expect($quote->fresh()->content)->toBe('Fixed my typo');
    expect($quote->fresh()->category_id)->toBe($newCategory->id);
});

test('a user cannot edit someone else\'s quote', function () {
    $quote = Quote::factory()->create(['content' => 'Original']);

    $this->actingAs(User::factory()->create())->put("/quotes/{$quote->id}", ['content' => 'Hacked', 'category_id' => $quote->category_id])
        ->assertForbidden();

    expect($quote->fresh()->content)->toBe('Original');
});

test('a user can delete their own quote', function () {
    $quote = Quote::factory()->create();

    $this->actingAs($quote->user)->from('/')->delete("/quotes/{$quote->id}")
        ->assertRedirect('/');

    expect(Quote::count())->toBe(0);
});

test('a user cannot delete someone else\'s quote', function () {
    $quote = Quote::factory()->create();

    $this->actingAs(User::factory()->create())->delete("/quotes/{$quote->id}")
        ->assertForbidden();

    expect(Quote::count())->toBe(1);
});

test('a user only sees edit and delete options on their own quotes', function () {
    $user = User::factory()->create();
    Quote::factory()->for($user)->create();
    Quote::factory()->create();

    $page = $this->actingAs($user)->get('/')->assertOk()->getContent();

    expect(substr_count($page, 'aria-label="Edit quote"'))->toBe(1);
    expect(substr_count($page, 'aria-label="Delete quote"'))->toBe(1);
});

test('quotes can be searched by text or author name', function () {
    Quote::factory()->create(['content' => 'Simplicity is the soul of efficiency.']);
    Quote::factory()->for(User::factory()->state(['name' => 'Zahra']))->create(['content' => 'Good design is invisible.']);
    Quote::factory()->create(['content' => 'Fall seven times, stand up eight.']);

    $this->get('/?search=simplicity')
        ->assertSee('Simplicity is the soul of efficiency.')
        ->assertDontSee('Good design is invisible.');

    $this->get('/?search=zahra')
        ->assertSee('Good design is invisible.')
        ->assertDontSee('Fall seven times, stand up eight.');
});

test('quotes can be filtered by category', function () {
    $love = Category::factory()->create(['name' => 'Love', 'slug' => 'love']);
    Quote::factory()->for($love)->create(['content' => 'Where there is love there is life.']);
    Quote::factory()->create(['content' => 'Fall seven times, stand up eight.']);

    $this->get('/?category=love')
        ->assertSee('Where there is love there is life.')
        ->assertDontSee('Fall seven times, stand up eight.');
});
