<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\About_school;
use App\Models\Campus;
use App\Models\CampusArea;
use App\Models\CisInNumber;
use App\Models\CISUpdate;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Tagline;
use Illuminate\Support\Facades\Cache;

class Index extends Controller
{
    public function index()
    {
        if (str_contains(strtolower(request()->header('Accept', '')), 'text/markdown')) {
            $markdown = <<<'MARKDOWN'
# Chalidana Islamic School

Chalidana Islamic School is an Islamic education institution in Gresik, East Java, Indonesia. Learn about our school, curriculum, campuses, admissions, events, and community.

## Key pages

- [About us](https://chalidanaislamicschool.sch.id/about-us)
- [Admission](https://chalidanaislamicschool.sch.id/admission)
- [Curricula](https://chalidanaislamicschool.sch.id/curricula)
- [School history](https://chalidanaislamicschool.sch.id/school-history)
- [School leadership](https://chalidanaislamicschool.sch.id/school-leadership)
- [Our campuses](https://chalidanaislamicschool.sch.id/school)
- [Events](https://chalidanaislamicschool.sch.id/events)
- [Contact](https://chalidanaislamicschool.sch.id/contact)
- [Terms](https://chalidanaislamicschool.sch.id/terms)
MARKDOWN;

            return response($markdown, 200, [
                'Content-Type' => 'text/markdown; charset=utf-8',
                'Link' => '<https://chalidanaislamicschool.sch.id/llms.txt>; rel="alternate"; type="text/plain", <https://chalidanaislamicschool.sch.id/>; rel="canonical"',
            ]);
        }

        $locale = current_locale();
        $cacheKey = 'landing.home.v2.' . $locale;
        $payload = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $tagline = Tagline::first();
            $aboutSchool = About_school::query()
                ->whereNull('deleted_at')
                ->latest('id')
                ->first();
            $campusAreas = CampusArea::query()
                ->whereNull('deleted_at')
                ->orderBy('title')
                ->get(['title', 'address', 'phone']);
            $campuses = Campus::with('campusArea:id,title')
                ->whereNull('deleted_at')
                ->orderBy('title')
                ->get();
            $cisInNumbers = CisInNumber::query()->latest()->get();
            $cisUpdates = CISUpdate::query()
                ->where('status', '1')
                ->latest()
                ->get();
            $events = Event::with('study:id,title')
                ->withCount('views')
                ->orderByDesc('tanggal')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(6)
                ->get();
            $testimonials = Testimonial::with(['grade:id,name', 'campus:id,title'])
                ->where('status', true)
                ->latest()
                ->take(9)
                ->get();

            return compact(
                'tagline',
                'aboutSchool',
                'campusAreas',
                'campuses',
                'cisInNumbers',
                'cisUpdates',
                'events',
                'testimonials'
            );
        });
        extract($payload);

        $testimonialExcerptLimit = $testimonials
            ->map(fn ($testimonial) => mb_strlen(trim(strip_tags($testimonial->content ?? ''))))
            ->filter(fn ($length) => $length > 0)
            ->min() ?? 120;

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return response()
            ->view('landing.index', compact('settings', 'tagline', 'aboutSchool', 'campusAreas', 'campuses', 'cisInNumbers', 'cisUpdates', 'events', 'testimonials', 'testimonialExcerptLimit'))
            ->header('Link', '<https://chalidanaislamicschool.sch.id/llms.txt>; rel="alternate"; type="text/plain", <https://chalidanaislamicschool.sch.id/>; rel="canonical"');
    }
}
