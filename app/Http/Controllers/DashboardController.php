<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramFollowers;

class DashboardController extends Controller
{
    public function index()
    {
        $followers = InstagramFollowers::groupBy('instagram_account')
            ->where('account_id', auth()->id())
            ->selectRaw('instagram_account, count(*) as total')
            ->get();



        return view('dashboard', compact('followers'));
    }
}
