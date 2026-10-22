<?php

use App\Models\Quote;
use App\Models\User;

test('a user can like and unlike a quote', function () {
    $user = User::factory()->create();
    $quote = Quote::factory()->create();

    $this->actingAs($user)->from('/')->post("/quotes/{$quote->id}/like")->assertRedirect('/');
    expect($quote->likers()->count())->toBe(1);

    $this->actingAs($user)->from('/')->post("/quotes/{$quote->id}/like")->assertRedirect('/');
    expect($quote->likers()->count())->toBe(0);
});

test('the feed shows how many people liked a quote and whether you did', function () {
    $user = User::factory()->create();
    $quote = Quote::factory()->create();
    $quote->likers()->attach([$user->id, User::factory()->create()->id]);

    $this->actingAs($user)->get('/')
        ->assertSee('aria-label="Unlike quote"', false)
        ->assertSeeInOrder(['aria-label="Unlike quote"', '<span>2</span>'], false);
});

test('a guest cannot like a quote', function () {
    $quote = Quote::factory()->create();

    $this->post("/quotes/{$quote->id}/like")->assertRedirect(route('login'));

    expect($quote->likers()->count())->toBe(0);
});

test('deleting a quote removes its likes', function () {
    $quote = Quote::factory()->create();
    $quote->likers()->attach(User::factory()->create());

    $quote->delete();

    $this->assertDatabaseCount('likes', 0);
});
