<?php

namespace App\Http\Controllers;
use App\Models\Vision_Mission;

use Illuminate\Http\Request;

class VisionMissionController extends Controller
{
    public function index()
    {
        $title = 'Vission & Mission';
        return view('admin.vision_mission', compact('title'));
    }

    public function data()
    {
        $visionMission = Vision_Mission::orderBy('created_at', 'desc')->get();
        return datatables()->of($visionMission)
            ->addIndexColumn()
            ->addColumn('action', function ($visionMission) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" onclick="editVisionMission(' . $visionMission->id . ')"><i class="bx bx-edit"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" onclick="deleteVisionMission(' . $visionMission->id . ')"><i class="bx bx-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        Vision_Mission::create([
            'title' => $request->title,
            'title_id' => $request->title_id,
            'content' => $request->content,
            'content_id' => $request->content_id,
        ]);

        return response()->json(['message' => 'Vission & Mission created successfully']);
    }

    public function edit($id)
    {
        $visionMission = Vision_Mission::findOrFail($id);
        return response()->json($visionMission);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $visionMission = Vision_Mission::findOrFail($id);
        $visionMission->update([
            'title' => $request->title,
            'title_id' => $request->title_id,
            'content' => $request->content,
            'content_id' => $request->content_id,
        ]);

        return response()->json(['message' => 'Vission & Mission updated successfully']);
    }

    public function destroy($id)
    {
        $visionMission = Vision_Mission::findOrFail($id);
        $visionMission->delete();

        return response()->json(['message' => 'Vission & Mission deleted successfully']);
    }
}
