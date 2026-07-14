<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;


class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getBlogs()
    {
        $blogs = \App\Models\Admin\Blogs::where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view(config('custom.front_template') . '.pages.blogs', compact('blogs'));
    }

    public function getBlogContent($slug)
    {
        $blog = \App\Models\Admin\Blogs::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view(config('custom.front_template') . '.pages.blog_content', compact('blog'));
    }
}
