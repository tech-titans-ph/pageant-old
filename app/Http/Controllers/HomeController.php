<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('admin.contests.index'));
        }

        if (auth('judge')->check()) {
            return redirect(route('judge.categories.index'));
        }

        return redirect(route('login'));
    }
}
