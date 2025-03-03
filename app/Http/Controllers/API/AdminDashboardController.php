<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_posts' => Post::count(),
            'total_members' => Member::count(),
            'total_comments' => Comment::count(),
            'total_ads' => Ad::count(),
        ]);
    }    
}
