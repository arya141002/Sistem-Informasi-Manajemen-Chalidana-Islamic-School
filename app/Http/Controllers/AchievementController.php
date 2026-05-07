<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AchievementController extends Controller
{
    public function index()
    {
        $title = 'Achievement';
        $campuses = Campus::query()->select('id', 'title')->orderBy('title')->get();
        return view('admin.achievement', compact('title', 'campuses'));
    }

    public function data()
    {
        $query = Achievement::with('campus:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editAchievement(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteAchievement(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action', 'content', 'content_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'nullable|exists:campuses,id',
            'tanggal' => 'nullable|date',
            'promoter' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $data = $request->only(['campus_id', 'tanggal', 'promoter', 'title', 'title_id', 'content', 'content_id']);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/achievement');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }

        Achievement::create($data);
        return response()->json(['message' => 'Achievement created successfully']);
    }

    public function edit($id)
    {
        $item = Achievement::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Achievement::findOrFail($id);

        $request->validate([
            'campus_id' => 'nullable|exists:campuses,id',
            'tanggal' => 'nullable|date',
            'promoter' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $data = $request->only(['campus_id', 'tanggal', 'promoter', 'title', 'title_id', 'content', 'content_id']);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/achievement');
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
        return response()->json(['message' => 'Achievement updated successfully']);
    }

    public function destroy($id)
    {
        $item = Achievement::findOrFail($id);
        if (!empty($item->img)) {
            $imagePath = public_path('images/achievement') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        return response()->json(['message' => 'Achievement deleted successfully']);
    }
}
