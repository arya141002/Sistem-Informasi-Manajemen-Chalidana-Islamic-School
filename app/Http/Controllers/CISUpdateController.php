<?php

namespace App\Http\Controllers;

use App\Models\CISUpdate;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class CISUpdateController extends Controller
{
    public function index(){
        $title = 'CIS Update';
        return view('admin.cis_update', compact('title'));
    }

    public function data(){
        $query = CISUpdate::latest()->get();
        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('img', function ($query) {
                return '<img src="' . asset('storage/' . $query->img) . '" width="100" class="img-thumbnail rounded shadow-sm" alt="Logo">';
            })
            ->addColumn('action', function ($query){
                return '<button class="btn btn-sm btn-primary edit-btn" onclick="editcisupdate(' . $query->id . ')"><i class="bx bx-edit"></i></button> ' .
                    '<button class="btn btn-sm btn-danger delete-btn" onclick="deletecisupdate(' . $query->id . ')"><i class="bx bx-trash"></i></button>';
            })
        ->rawColumns(['action', 'img'])
        ->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'img'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1',
            'url' => 'required|url|max:250'
        ]);

        $data = $request->all();

        if ($request->hasFile('img')) {
            // Simpan ke storage/app/public/images/cis_updates
            $data['img'] = $request->file('img')->store('images/popup', 'public');
        }

        CISUpdate::create($data);
        return response()->json(['message' => 'CIS Update created successfully']);
    }

    public function edit($id){
        $cisupdate = CISUpdate::findOrFail($id);
        return response()->json($cisupdate);
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'img'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1',
            'url' => 'required|url|max:250'
        ]);

        $cisupdate = CISUpdate::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('img')) {
            // 1. Hapus gambar lama jika ada
            if ($cisupdate->img && Storage::disk('public')->exists($cisupdate->img)) {
                Storage::disk('public')->delete($cisupdate->img);
            }
            
            // 2. Simpan gambar baru
            $data['img'] = $request->file('img')->store('images/popup', 'public');
        } else {
            // Tetap gunakan gambar lama jika tidak ada upload baru
            $data['img'] = $cisupdate->img;
        }
        $cisupdate->update($data);

        return response()->json(['message' => 'CIS Update updated successfully']);
    }

    public function updateStatus(Request $request, $id){
        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $cisupdate = CISUpdate::findOrFail($id);
        $cisupdate->status = $request->status;
        $cisupdate->save();

        return response()->json(['message' => 'CIS Update status updated successfully']);
    }

    public function destroy($id){
        $cisupdate = CISUpdate::findOrFail($id);

        // Hapus file gambar dari storage sebelum menghapus data dari database
        if ($cisupdate->img && Storage::disk('public')->exists($cisupdate->img)) {
            Storage::disk('public')->delete($cisupdate->img);
        }

        $cisupdate->delete();

        return response()->json(['message' => 'CIS Update deleted successfully']);
    }
}
