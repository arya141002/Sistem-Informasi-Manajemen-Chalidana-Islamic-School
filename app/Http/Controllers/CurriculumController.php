<?php

namespace App\Http\Controllers;
use App\Models\Curriculum;

use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        $title = 'Curriculum';
        return view('admin.curriculum', compact('title'));
    }

    public function data()
    {
        $curriculums = Curriculum::orderBy('created_at', 'desc')->get();
        return datatables()->of($curriculums)
            ->addIndexColumn()
            ->addColumn('action', function ($curriculum) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" onclick="editCurriculum(' . $curriculum->id . ')"><i class="bx bx-edit"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" onclick="deleteCurriculum(' . $curriculum->id . ')"><i class="bx bx-trash"></i></button>
                ';
            })
            ->rawColumns(['action', 'content', 'content_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        Curriculum::create($request->all());

        return response()->json(['message' => 'Curriculum created successfully']);
    }

    public function edit($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        return response()->json($curriculum);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        $curriculum = Curriculum::findOrFail($id);
        $curriculum->update($request->all());

        return response()->json(['message' => 'Curriculum updated successfully']);
    }

    public function destroy($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->delete();

        return response()->json(['message' => 'Curriculum deleted successfully']);
    }
}
