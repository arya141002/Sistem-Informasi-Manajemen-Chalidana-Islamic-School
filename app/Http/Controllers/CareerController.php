<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $title = 'Career';
        return view('admin.career', compact('title'));
    }

    public function data()
    {
        $query = Career::query()->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editCareer(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteCareer(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action', 'content', 'content_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
            'career_status' => 'required|integer',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        Career::create($request->only(['tanggal', 'career_status', 'title', 'title_id', 'content', 'content_id']));
        return response()->json(['message' => 'Career created successfully']);
    }

    public function edit($id)
    {
        $item = Career::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
            'career_status' => 'required|integer',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $item = Career::findOrFail($id);
        $item->update($request->only(['tanggal', 'career_status', 'title', 'title_id', 'content', 'content_id']));
        return response()->json(['message' => 'Career updated successfully']);
    }

    public function destroy($id)
    {
        $item = Career::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Career deleted successfully']);
    }
}
