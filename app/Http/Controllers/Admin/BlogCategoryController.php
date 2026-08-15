<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.blog-categories.index', [
            'categories' => BlogCategory::query()->withCount('blogs')->latest()->get(),
            'categoryToEdit' => null,
        ]);
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('status', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.blog-categories.index', [
            'categories' => BlogCategory::query()->withCount('blogs')->latest()->get(),
            'categoryToEdit' => $blogCategory,
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')->with('status', 'Category deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}
