<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Grade;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index()
    {
        $title = 'Testimonial';
        $campuses = Campus::query()->select('id', 'title')->orderBy('title')->get();
        $grades = Grade::query()->select('id', 'name')->orderBy('name')->get();

        return view('admin.testimonial', compact('title', 'campuses', 'grades'));
    }

    public function data()
    {
        $query = Testimonial::with(['grade:id,name', 'campus:id,title'])->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('grade_name', fn ($item) => optional($item->grade)->name ?: '-')
            ->addColumn('campus_name', fn ($item) => optional($item->campus)->title ?: '-')
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary edit-btn" onclick="editTestimonial(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger delete-btn" onclick="deleteTestimonial(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->editColumn('rating', fn ($item) => $item->rating ?: '-')
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'nullable|exists:campuses,id',
            'grade_id' => 'nullable|exists:grades,id',
            'title' => 'required|string|max:200',
            'student' => 'required|string|max:200',
            'content' => 'required|string',
            'status' => 'nullable|boolean',
            'rating' => 'required|integer|min:1|max:5',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['campus_id', 'grade_id', 'student', 'title', 'content', 'status', 'rating']);

        if ($request->hasFile('images')) {
            $destinationPath = public_path('images/testimonial');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('images')->extension();
            $request->file('images')->move($destinationPath, $imageName);
            $data['images'] = $imageName;
        }

        $data['status'] = $request->boolean('status');

        Testimonial::create($data);

        return response()->json(['message' => 'Testimonial created successfully']);
    }

    public function edit($id)
    {
        $item = Testimonial::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Testimonial::findOrFail($id);

        $request->validate([
            'campus_id' => 'nullable|exists:campuses,id',
            'grade_id' => 'nullable|exists:grades,id',
            'title' => 'required|string|max:200',
            'student' => 'required|string|max:200',
            'content' => 'required|string',
            'status' => 'nullable|boolean',
            'rating' => 'required|integer|min:1|max:5',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['campus_id', 'grade_id', 'student', 'title', 'content', 'status', 'rating']);

        if ($request->hasFile('images')) {
            $destinationPath = public_path('images/testimonial');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($item->images)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $item->images;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('images')->extension();
            $request->file('images')->move($destinationPath, $imageName);
            $data['images'] = $imageName;
        }

        $data['status'] = $request->boolean('status');

        $item->update($data);

        return response()->json(['message' => 'Testimonial updated successfully']);
    }

    public function destroy($id)
    {
        $item = Testimonial::findOrFail($id);
        if (!empty($item->images)) {
            $imagePath = public_path('images/testimonial') . DIRECTORY_SEPARATOR . $item->images;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();

        return response()->json(['message' => 'Testimonial deleted successfully']);
    }
}
