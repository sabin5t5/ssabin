<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->account_type == 1) {
            return redirect(route('admin.dashboard'));
        } elseif (Auth::user()->account_type == 0) {
            return redirect(route('admin.dashboard'));
        }
        else
        {
            return redirect(route('main'));
        }
    }
}
