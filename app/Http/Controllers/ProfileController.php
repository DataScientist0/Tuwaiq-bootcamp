<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show a user's profile with the quotes they shared.
     */
    public function show(Request $request, User $user): View
    {
        $quotes = $user->quotes()->forFeed($request->user())->get();

        return view('profile', [
            'page' => 'Profile',
            'user' => $user,
            'quotes' => $quotes,
            'categories' => Category::orderBy('name')->get(),
            'likesReceived' => $quotes->sum('likers_count'),
            'editingQuoteId' => $request->integer('edit'),
        ]);
    }
}
