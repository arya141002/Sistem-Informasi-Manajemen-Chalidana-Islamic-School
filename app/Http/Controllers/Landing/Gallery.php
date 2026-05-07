<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\Gallery as GalleryModel;
use App\Models\GalleryCategory;
use App\Models\Tagline;
use Illuminate\Http\Request;

class Gallery extends Controller
{
    private function sharedData(): array
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return compact('tagline', 'campusAreas', 'settings');
    }

    public function index(Request $request)
    {
        $categories = GalleryCategory::query()
            ->withCount('galleries')
            ->with([
                'galleries' => fn ($query) => $query
                    ->select('id', 'img_id', 'img', 'title', 'title_id')
                    ->latest()
                    ->take(1),
            ])
            ->orderBy('title')
            ->get(['id', 'title', 'title_id', 'slug', 'img', 'location']);

        return view('landing.gallery', array_merge(
            $this->sharedData(),
            compact('categories')
        ));
    }

    public function show(string $slug)
    {
        $category = GalleryCategory::query()
            ->withCount('galleries')
            ->where('slug', $slug)
            ->firstOrFail(['id', 'title', 'title_id', 'slug', 'img', 'location']);

        $items = GalleryModel::query()
            ->with('category:id,title,title_id,slug,location')
            ->where('img_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $relatedCategories = GalleryCategory::query()
            ->withCount('galleries')
            ->with([
                'galleries' => fn ($query) => $query
                    ->select('id', 'img_id', 'img', 'title', 'title_id')
                    ->latest()
                    ->take(1),
            ])
            ->where('id', '!=', $category->id)
            ->orderBy('title')
            ->take(6)
            ->get(['id', 'title', 'title_id', 'slug', 'img', 'location']);

        $pageTitle = __('front.gallery_category_title', ['category' => localized_value($category, 'title')]);
        $pageDesc = $category->location ?: __('front.gallery_category_desc_fallback');

        return view('landing.gallery_detail', array_merge(
            $this->sharedData(),
            compact('category', 'items', 'relatedCategories', 'pageTitle', 'pageDesc')
        ));
    }
}
