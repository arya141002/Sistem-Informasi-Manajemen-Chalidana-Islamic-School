<?php

namespace App\Http\Controllers;
use App\Models\About_school;

use Illuminate\Http\Request;

class AboutSchoolController extends Controller
{
    public function index()
    {
        $title = 'About School Management';
        return view('admin/about_school', compact('title'));
    }

    public function data(){
        $query = About_school::query();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary edit-btn" onclick="editAbout(' . $item->id . ')"><i class="bx bx-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteAbout(' . $item->id . ')"><i class="bx bx-trash"></i></button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        About_school::create([
            'title' => $request->title,
            'title_id' => $request->title_id,
            'content' => $request->content,
            'content_id' => $request->content_id,
        ]);

        return response()->json(['message' => 'About School information saved successfully']);
    }

    public function edit($id){
        $about = About_school::findOrFail($id);
        return response()->json($about);
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $about = About_school::findOrFail($id);
        $about->update([
            'title' => $request->title,
            'title_id' => $request->title_id,
            'content' => $request->content,
            'content_id' => $request->content_id,
        ]);

        return response()->json(['message' => 'About School information updated successfully']);
    }

    public function destroy($id){
        $about = About_school::findOrFail($id);
        $about->delete();
        return response()->json(['message' => 'About School information deleted successfully']);
    }
}
