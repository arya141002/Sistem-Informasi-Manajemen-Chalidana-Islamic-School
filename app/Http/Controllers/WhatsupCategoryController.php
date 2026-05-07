<?php

namespace App\Http\Controllers;
use App\Models\WhatsupCategory;

use Illuminate\Http\Request;

class WhatsupCategoryController extends Controller
{
    public function index(){
        $title = 'Whatsup Category';
        return view('admin.whatsup_category', compact('title'));
    }

    public function data(){
        $query = WhatsupCategory::query()->latest();
        return datatables($query)
        ->addIndexColumn()
        ->addColumn('action', function($whatsup_category) {
            return '<button class="btn btn-sm btn-primary" onclick="editx(' . $whatsup_category->id . ')"><i class="bx bx-edit"></i></button> ' .
                    '<button class="btn btn-sm btn-danger" onclick="deletex(' . $whatsup_category->id . ')"><i class="bx bx-trash"></i></button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'slug' => 'required|string|max:200|unique:whatsup_category,slug',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        WhatsupCategory::create([
            'slug' => $request->slug,
            'title' => $request->title,
            'title_id' => $request->title_id,
        ]);

        return response()->json(['message' => 'Whatsup category created successfully']);
    }

    public function edit($id){
        $whatsupCategory = WhatsupCategory::findOrFail($id);
        return response()->json($whatsupCategory);
    }

    public function update(Request $request, $id){
        $request->validate([
            'slug' => 'required|string|max:200|unique:whatsup_category,slug,' . $id,
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
        ]);

        $whatsupCategory = WhatsupCategory::findOrFail($id);
        $whatsupCategory->update([
            'slug' => $request->slug,
            'title' => $request->title,
            'title_id' => $request->title_id,
        ]);

        return response()->json(['message' => 'Whatsup category updated successfully']);
    }

    public function destroy($id){
        $whatsupCategory = WhatsupCategory::findOrFail($id);
        $whatsupCategory->delete();

        return response()->json(['message' => 'Whatsup category deleted successfully']);
    }
}
