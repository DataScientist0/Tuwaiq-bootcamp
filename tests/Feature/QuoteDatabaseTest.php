<?php

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

test('a user has many quotes and each quote belongs to its user', function () {
    $user = User::factory()->has(Quote::factory()->count(3))->create();

    expect($user->quotes)->toHaveCount(3);
    expect($user->quotes->first()->user->is($user))->toBeTrue();
});

test('a quote must reference an existing user', function () {
    DB::table('quotes')->insert(['user_id' => 999, 'content' => 'Keep going']);
})->throws(QueryException::class);

test('a quote must have content', function () {
    $user = User::factory()->create();

    DB::table('quotes')->insert(['user_id' => $user->id, 'content' => null]);
})->throws(QueryException::class);

test('two users cannot share the same email', function () {
    User::factory()->create(['email' => 'sara@example.com']);
    User::factory()->create(['email' => 'sara@example.com']);
})->throws(QueryException::class);

test('deleting a user deletes their quotes', function () {
    $sara = User::factory()->has(Quote::factory()->count(2))->create();
    $ahmed = User::factory()->has(Quote::factory())->create();

    $sara->delete();

    expect(Quote::count())->toBe(1);
    expect(Quote::first()->user_id)->toBe($ahmed->id);
});
