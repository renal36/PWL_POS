<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activemenu = 'dashboard';

        return view('welcome', compact('breadcrumb', 'activemenu'));
    }
}