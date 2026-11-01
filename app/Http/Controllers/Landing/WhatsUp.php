<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\Tagline;
use App\Models\Whatsup as WhatsupModel;
use App\Models\WhatsupCategory;
use App\Support\ContentViewTracker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WhatsUp extends Controller
{
    private const SECTION_ALIASES = [
        'happenings' => ['happenings', 'happening'],
        'highlights' => ['highlights', 'highlight'],
        'announcements' => ['announcements', 'announcement'],
    ];

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

    private function resolveCategory(string $section): ?WhatsupCategory
    {
        $aliases = self::SECTION_ALIASES[$section] ?? [$section];

        return WhatsupCategory::query()
            ->where(function (Builder $query) use ($aliases) {
                foreach ($aliases as $alias) {
                    $query->orWhere('slug', $alias)
                        ->orWhere('title', 'like', '%' . $alias . '%')
                        ->orWhere('title_id', 'like', '%' . $alias . '%');
                }
            })
            ->orderBy('id')
            ->first();
    }

    private function pageMeta(string $section): array
    {
        return match ($section) {
            'happenings' => [
                'title' => __('front.happenings'),
                'pageTitle' => __('front.happenings_page_title'),
                'pageDesc' => __('front.happenings_page_desc'),
            ],
            'highlights' => [
                'title' => __('front.highlights'),
                'pageTitle' => __('front.highlights_page_title'),
                'pageDesc' => __('front.highlights_page_desc'),
            ],
            default => [
                'title' => __('front.announcements'),
                'pageTitle' => __('front.announcements_page_title'),
                'pageDesc' => __('front.announcements_page_desc'),
            ],
        };
    }

    public function index(Request $request, string $section)
    {
        abort_unless(array_key_exists($section, self::SECTION_ALIASES), 404);

        $category = $this->resolveCategory($section);
        $search = trim((string) $request->query('q', ''));

        $items = WhatsupModel::query()
            ->with('category:id,slug,title,title_id')
            ->withCount('views')
            ->when($category, fn (Builder $query) => $query->where('news_id', $category->id))
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $inner) use ($search) {
                    $inner->where('title', 'like', '%' . $search . '%')
                        ->orWhere('title_id', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('content_id', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('landing.whatsup_index', array_merge(
            $this->sharedData(),
            $this->pageMeta($section),
            compact('items', 'section', 'search', 'category')
        ));
    }

    public function show(string $section, string $slug)
    {
        abort_unless(array_key_exists($section, self::SECTION_ALIASES), 404);

        $category = $this->resolveCategory($section);

        $item = WhatsupModel::query()
            ->with('category:id,slug,title,title_id')
            ->when($category, fn (Builder $query) => $query->where('news_id', $category->id))
            ->where('slug', $slug)
            ->firstOrFail();

        app(ContentViewTracker::class)->record(WhatsupModel::class, $item->id, request());
        $item->loadCount('views');

        $relatedItems = WhatsupModel::query()
            ->with('category:id,slug,title,title_id')
            ->where('id', '!=', $item->id)
            ->when($item->news_id, fn (Builder $query) => $query->where('news_id', $item->news_id))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('landing.whatsup_detail', array_merge(
            $this->sharedData(),
            $this->pageMeta($section),
            compact('item', 'relatedItems', 'section')
        ));
    }
}
