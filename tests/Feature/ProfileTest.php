<?php

use App\Models\Quote;
use App\Models\User;

test('anyone can view a profile with the user\'s quotes and stats', function () {
    $sara = User::factory()->create(['name' => 'Sara']);
    $quotes = Quote::factory()->for($sara)->count(2)->create();
    $quotes->first()->likers()->attach(User::factory()->count(3)->create());
    $otherQuote = Quote::factory()->create();

    $this->get("/users/{$sara->id}")
        ->assertOk()
        ->assertSee('Sara')
        ->assertSee($quotes[0]->content)
        ->assertSee($quotes[1]->content)
        ->assertDontSee($otherQuote->content)
        ->assertSeeInOrder(['Quotes', '2', 'Likes received', '3']);
});

test('a user sees edit options and the share button on their own profile only', function () {
    $sara = User::factory()->create();
    Quote::factory()->for($sara)->create();
    $ahmed = User::factory()->create();

    $this->actingAs($sara)->get("/users/{$sara->id}")
        ->assertSee('Edit quote')
        ->assertSee('Share a quote');

    $this->actingAs($ahmed)->get("/users/{$sara->id}")
        ->assertDontSee('Edit quote')
        ->assertDontSee('Share a quote');
});

test('an unknown profile returns not found', function () {
    $this->get('/users/999')->assertNotFound();
});
