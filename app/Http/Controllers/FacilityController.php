<?php

namespace App\Http\Controllers;
use App\Models\Facility;
use App\Models\Campus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FacilityController extends Controller
{
    public function index()
    {
        $campuses = Campus::query()->select('id', 'title')->orderBy('title')->get();
        $title = 'Facilities';
        return view('admin.facilities', compact('campuses', 'title'));
    }

    public function data()
    {
        $query = Facility::with('campus:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($facility) {
                return '<button class="btn btn-sm btn-primary" onclick="editFacility(' . $facility->id . ')"><i class="bx bx-edit"></i></button> ' .
                    '<button class="btn btn-sm btn-danger" onclick="deleteFacility(' . $facility->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'campus_id' => 'required|exists:campuses,id',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $facility = new Facility();
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/facility');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->img->extension();
            $request->img->move($destinationPath, $imageName);
            $facility->img = $imageName;
        }
        $facility->campus_id = $request->campus_id;
        $facility->title = $request->title;
        $facility->title_id = $request->title_id;
        $facility->content = $request->content;
        $facility->content_id = $request->content_id;
        $facility->save();

        return response()->json(['message' => 'Facility created successfully']);
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return response()->json($facility);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'campus_id' => 'required|exists:campuses,id',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $facility = Facility::findOrFail($id);
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/facility');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($facility->img)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $facility->img;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->img->extension();
            $request->img->move($destinationPath, $imageName);
            $facility->img = $imageName;
        }
        $facility->campus_id = $request->campus_id;
        $facility->title = $request->title;
        $facility->title_id = $request->title_id;
        $facility->content = $request->content;
        $facility->content_id = $request->content_id;
        $facility->save();

        return response()->json(['message' => 'Facility updated successfully']);
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if (!empty($facility->img)) {
            $imagePath = public_path('images/facility') . DIRECTORY_SEPARATOR . $facility->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $facility->delete();
        return response()->json(['message' => 'Facility deleted successfully']);
    }
}
