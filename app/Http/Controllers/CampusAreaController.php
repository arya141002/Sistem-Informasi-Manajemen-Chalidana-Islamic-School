<?php

namespace App\Http\Controllers;
use App\Models\CampusArea;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampusAreaController extends Controller
{
    public function index(){
        $title = 'Campus Area';
        return view('admin.campus_area', compact('title'));
    }

    public function data(){
        $query = CampusArea::query();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($item){
                return '
                    <button class="btn btn-sm btn-primary" onclick="editForm('. $item->id .')"><i class="bx bx-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteData('. $item->id .')"><i class="bx bx-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campus_area,slug',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pagehead' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'address_id' => 'required|string',
        ]);

        $data = $request->only('title', 'slug', 'pagehead', 'phone', 'address', 'address_id');

        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('images/campus_area', 'public');
            $data['img'] = $imagePath;
        }

        CampusArea::create($data);
        return response()->json(['message' => 'Campus area created successfully']);
    }

    public function edit($id){
        $campusArea = CampusArea::findOrFail($id);
        return response()->json($campusArea);
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campus_area,slug,' . $id,
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pagehead' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'address_id' => 'required|string',
        ]);

        $campusArea = CampusArea::findOrFail($id);
        $data = $request->only('title', 'slug', 'pagehead', 'phone', 'address', 'address_id');

        if ($request->hasFile('img')) {
            if (!empty($campusArea->img) && Storage::disk('public')->exists($campusArea->img)) {
                Storage::disk('public')->delete($campusArea->img);
            }
            $imagePath = $request->file('img')->store('images/campus_area', 'public');
            $data['img'] = $imagePath;
        }

        $campusArea->update($data);
        return response()->json(['message' => 'Campus area updated successfully']);
    }

    public function destroy($id){
        $campusArea = CampusArea::findOrFail($id);
        if (!empty($campusArea->img) && Storage::disk('public')->exists($campusArea->img)) {
            Storage::disk('public')->delete($campusArea->img);
        }
        $campusArea->delete();
        return response()->json(['message' => 'Campus area deleted successfully']);
    }
}
