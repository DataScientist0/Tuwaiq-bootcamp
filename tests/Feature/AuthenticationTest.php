<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a guest can sign up and is logged in', function () {
    $response = $this->post('/signup', [
        'name' => 'Sara Ahmed',
        'email' => 'sara@example.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();

    $user = User::where('email', 'sara@example.com')->first();
    expect($user->name)->toBe('Sara Ahmed');
    expect(Hash::check('secret123', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('secret123');
});

test('sign up rejects invalid fields', function (array $fields, string $invalidField) {
    $response = $this->from('/signup')->post('/signup', [
        'name' => 'Sara',
        'email' => 'sara@example.com',
        'password' => 'secret123',
        ...$fields,
    ]);

    $response->assertRedirect('/signup');
    $response->assertSessionHasErrors($invalidField);
    $this->assertGuest();
})->with([
    'name with numbers' => [['name' => 'Sara1'], 'name'],
    'name too short' => [['name' => 'S'], 'name'],
    'name too long' => [['name' => str_repeat('a', 51)], 'name'],
    'invalid email' => [['email' => 'not-an-email'], 'email'],
    'short password' => [['password' => 'short'], 'password'],
]);

test('sign up rejects an email that is already registered', function () {
    User::factory()->create(['email' => 'sara@example.com']);

    $response = $this->post('/signup', [
        'name' => 'Sara',
        'email' => 'sara@example.com',
        'password' => 'secret123',
    ]);

    $response->assertSessionHasErrors(['email' => 'This email is already registered.']);
});

test('a user can log in and sees their name', function () {
    $user = User::factory()->create(['name' => 'Sara', 'password' => 'secret123']);

    $response = $this->post('/login', ['email' => $user->email, 'password' => 'secret123']);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
    $this->get('/')->assertSee('Sara')->assertSee('Log Out');
});

test('a wrong password keeps the user on the log in page with an error', function () {
    $user = User::factory()->create(['password' => 'secret123']);

    $response = $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a user can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});
