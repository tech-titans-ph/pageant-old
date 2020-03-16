<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isAn('admin')) {
                return redirect(route('admin.contests.index'));
            }

            if (auth()->user()->isA('judge')) {
                return redirect(route('judge.categories.index'));
            }
        }

        return redirect(route('login'));
    }
}
