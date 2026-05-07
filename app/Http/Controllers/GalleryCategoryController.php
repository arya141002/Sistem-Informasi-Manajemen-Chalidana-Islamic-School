<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryCategoryController extends Controller
{
    public function index()
    {
        $title = 'Gallery Category';
        return view('admin.gallery_category', compact('title'));
    }

    public function data()
    {
        $query = GalleryCategory::query()->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editCategory(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'study_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'slug' => 'required|string|max:200|unique:studies,slug',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|string|max:200',
        ]);

        $data = $request->only(['study_id', 'title', 'title_id', 'slug', 'location']);
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/gallery');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }

        GalleryCategory::create($data);
        return response()->json(['message' => 'Gallery category created successfully']);
    }

    public function edit($id)
    {
        $item = GalleryCategory::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = GalleryCategory::findOrFail($id);

        $request->validate([
            'study_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'slug' => 'required|string|max:200|unique:studies,slug,' . $id,
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|string|max:200',
        ]);

        $data = $request->only(['study_id', 'title', 'title_id', 'slug', 'location']);
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/gallery');
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
        return response()->json(['message' => 'Gallery category updated successfully']);
    }

    public function destroy($id)
    {
        $item = GalleryCategory::findOrFail($id);
        if (!empty($item->img)) {
            $imagePath = public_path('images/gallery') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        return response()->json(['message' => 'Gallery category deleted successfully']);
    }
}
