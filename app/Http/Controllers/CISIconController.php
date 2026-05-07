<?php

namespace App\Http\Controllers;
use App\Models\CISIcon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CISIconController extends Controller
{
    public function index(){
        $title = 'CIS Icon';
        return view('admin.cis_icon', compact('title'));
    }

    public function data(){
        $query = CISIcon::latest()->get();
        return datatables()->of($query)
        ->addIndexColumn()
        ->addColumn('action', function($query){
            return '<button class="btn btn-sm btn-primary edit-btn" onclick="editcisicon(' . $query->id . ')"><i class="bx bx-edit"></i></button> ' .
                    '<button class="btn btn-sm btn-danger delete-btn" onclick="deletecisicon(' . $query->id . ')"><i class="bx bx-trash"></i></button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['title']);
        if($request->hasFile('file')) {
            $destinationPath = public_path('images/icon');
            if(!File::exists($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('file')->extension();
            $request->file('file')->move($destinationPath, $imageName);
            $data['file'] = $imageName;
        }

        CISIcon::create($data);
        return response()->json(['message' => 'CIS Icon created successfully']);
    }

    public function edit($id){
        $item = CISIcon::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id){
        $item = CISIcon::findOrFail($id);

        $data = $request->only(['title']);
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('file')) {
            $destinationPath = public_path('images/icon');
            if(!File::exists($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($item->file)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $item->file;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('file')->extension();
            $request->file('file')->move($destinationPath, $imageName);
            $data['file'] = $imageName;

            }
            $item->update($data);
            return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy($id){
        $item = CISIcon::findOrFail($id);
        if(!empty($item->file)){
            $imagePath = public_path('images/icon') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        return response()->json(['message' => 'CIS Icon deleted successfully']);
    }
}
