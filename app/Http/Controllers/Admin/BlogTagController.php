<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogTagRequest;
use App\Http\Requests\UpdateBlogTagRequest;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(): View
    {
        return view('admin.blog-tags.index', [
            'tags' => BlogTag::query()->withCount('blogs')->latest()->get(),
            'tagToEdit' => null,
        ]);
    }

    public function store(StoreBlogTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        BlogTag::create($data);

        return redirect()->route('admin.blog-tags.index')->with('status', 'Tag created successfully.');
    }

    public function edit(BlogTag $blogTag): View
    {
        return view('admin.blog-tags.index', [
            'tags' => BlogTag::query()->withCount('blogs')->latest()->get(),
            'tagToEdit' => $blogTag,
        ]);
    }

    public function update(UpdateBlogTagRequest $request, BlogTag $blogTag): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        $blogTag->update($data);

        return redirect()->route('admin.blog-tags.index')->with('status', 'Tag updated successfully.');
    }

    public function destroy(BlogTag $blogTag): RedirectResponse
    {
        $blogTag->delete();

        return redirect()->route('admin.blog-tags.index')->with('status', 'Tag deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}
