<?php

namespace App\Http\Controllers;
use App\Models\TeacherAndStaff;
use App\Models\Campus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TeacherAndStaffController extends Controller
{
    public function index()
    {
        $title = 'Teacher and Staff';
        $teachersAndStaff = TeacherAndStaff::all();
        $campuses = Campus::all();
        return view('admin.teacher_and_staff', compact('title', 'teachersAndStaff', 'campuses'));
    }

    public function data()
    {
        $query = TeacherAndStaff::with('campus:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '
                    <button class="btn btn-sm btn-primary" onclick="edit(' . $item->id . ')">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(' . $item->id . ')">Delete</button>
                ';
            })
            ->rawColumns(['action', 'content'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'campus_id' => 'required|exists:campuses,id',
            'role' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $destinationPath = public_path('images/teachernstaff');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
        $request->file('img')->move($destinationPath, $imageName);

        TeacherAndStaff::create([
            'img' => $imageName,
            'campus_id' => $request->campus_id,
            'role' => $request->role,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Teacher and Staff created successfully']);
    }

    public function edit($id)
    {
        $teacherAndStaff = TeacherAndStaff::findOrFail($id);
        return response()->json($teacherAndStaff);
    }

    public function update(Request $request, $id)
    {
        $teacherAndStaff = TeacherAndStaff::findOrFail($id);

        $request->validate([
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'campus_id' => 'required|exists:campuses,id',
            'role' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/teachernstaff');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($teacherAndStaff->img)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $teacherAndStaff->img;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $teacherAndStaff->img = $imageName;
        }

        $teacherAndStaff->campus_id = $request->campus_id;
        $teacherAndStaff->role = $request->role;
        $teacherAndStaff->content = $request->content;
        $teacherAndStaff->save();

        return response()->json(['message' => 'Teacher and Staff updated successfully']);
    }

    public function destroy($id)
    {
        $teacherAndStaff = TeacherAndStaff::findOrFail($id);

        if (!empty($teacherAndStaff->img)) {
            $imagePath = public_path('images/teachernstaff') . DIRECTORY_SEPARATOR . $teacherAndStaff->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $teacherAndStaff->delete();
        return response()->json(['message' => 'Teacher and Staff deleted successfully']);
    }
}
