<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function welcome()
    {
        $top6 = Pizza::with('ingredients')->latest()->take(6)->get();

        return view('welcome', compact('top6'));
    }
}
