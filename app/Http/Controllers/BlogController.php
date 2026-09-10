<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function show(Blog $blog): View
    {
        return view('blog-show', [
            'blog' => $blog,
        ]);
    }
}
