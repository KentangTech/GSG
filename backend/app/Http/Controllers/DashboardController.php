<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $news = News::latest()->take(3)->get();
        return view('dashboard', compact('news'));
    }
}
