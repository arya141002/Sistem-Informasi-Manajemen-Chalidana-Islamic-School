<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    public function index()
    {
        $title = 'Gallery';
        $categories = GalleryCategory::query()
        ->select('id', 'title', 'created_at')
        ->latest() // atau ->orderBy('created_at', 'desc')
        ->get();
        return view('admin.gallery', compact('title', 'categories'));
    }

    public function data()
    {
        $query = Gallery::with('category:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editGallery(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteGallery(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'img_id' => 'required|exists:studies,id',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        $data = $request->only(['img_id', 'title', 'title_id']);
        $destinationPath = public_path('images/gallery/detail');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
        $request->file('img')->move($destinationPath, $imageName);
        $data['img'] = $imageName;

        Gallery::create($data);
        return response()->json(['message' => 'Gallery created successfully']);
    }

    public function edit($id)
    {
        $item = Gallery::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Gallery::findOrFail($id);

        $request->validate([
            'img_id' => 'required|exists:studies,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        $data = $request->only(['img_id', 'title', 'title_id']);
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/gallery/detail');
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
        return response()->json(['message' => 'Gallery updated successfully']);
    }

    public function destroy($id)
    {
        $item = Gallery::findOrFail($id);
        if (!empty($item->img)) {
            $imagePath = public_path('images/gallery/detail') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        return response()->json(['message' => 'Gallery deleted successfully']);
    }
}
