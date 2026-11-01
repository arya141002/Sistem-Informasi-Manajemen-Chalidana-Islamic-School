<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CategoryStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $title = 'Events';
        $studies = CategoryStudy::query()->select('id', 'title')->orderBy('title')->get();
        return view('admin.events', compact('title', 'studies'));
    }

    public function data()
    {
        $query = Event::with('study:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editEvent(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteEvent(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action', 'content', 'content_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'study_id' => 'required|exists:category_studies,id',
            'tanggal' => 'nullable|date',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);

        $data = $request->only(['study_id', 'tanggal', 'title', 'title_id', 'content', 'content_id']);
        $data['slug'] = $this->generateUniqueSlug($request->title);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/events');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }

        Event::create($data);
        $this->clearLandingHomeCache();
        return response()->json(['message' => 'Event created successfully']);
    }

    public function edit($id)
    {
        $item = Event::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Event::findOrFail($id);

        $request->validate([
            'study_id' => 'required|exists:category_studies,id',
            'tanggal' => 'nullable|date',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);

        $data = $request->only(['study_id', 'tanggal', 'title', 'title_id', 'content', 'content_id']);
        $data['slug'] = $this->generateUniqueSlug($request->title, $item->id);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/events');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($item->img)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $item->img;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }

        $item->update($data);
        $this->clearLandingHomeCache();
        return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy($id)
    {
        $item = Event::findOrFail($id);
        if (!empty($item->img)) {
            $imagePath = public_path('images/events') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        $this->clearLandingHomeCache();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    private function clearLandingHomeCache(): void
    {
        foreach (['id', 'en'] as $locale) {
            Cache::forget('landing.home.v2.' . $locale);
        }
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base !== '' ? $base : 'event';
        $counter = 2;

        $query = Event::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $exists = $query->where('slug', $slug)->exists();
        while ($exists) {
            $slug = $base !== '' ? $base . '-' . $counter : 'event-' . $counter;
            $counter++;
            $exists = $query->where('slug', $slug)->exists();
        }

        return $slug;
    }
}
