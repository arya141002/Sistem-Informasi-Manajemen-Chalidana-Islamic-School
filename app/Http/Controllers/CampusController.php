<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\CampusArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CampusController extends Controller
{
    public function index()
    {
        $title = 'Campus';
        $campusAreas = CampusArea::query()->select('id', 'title')->orderBy('title')->get();
        return view('admin.campus', compact('title', 'campusAreas'));
    }

    public function data()
    {
        $query = Campus::query();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editCampus(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteCampus(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'catcampus_id' => 'required|exists:campus_area,id',
            'title' => 'required|string|max:255',
            'NPSN' => 'nullable|string|max:255',
            'school_tour' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:campuses,slug',
            'img_header' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'from' => 'nullable|string|max:255',
            'from_id' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'message_id' => 'nullable|string',
            'address' => 'nullable|string',
            'maplocation' => 'nullable|string',
            'content' => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);

        $destinationPath = public_path('images/campuses');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        if ($request->hasFile('img_header')) {
            $headerName = 'header_' . time() . '_' . uniqid() . '.' . $request->file('img_header')->extension();
            $request->file('img_header')->move($destinationPath, $headerName);
            $validatedData['img_header'] = $headerName;
        }

        if ($request->hasFile('img_principal')) {
            $principalName = 'principal_' . time() . '_' . uniqid() . '.' . $request->file('img_principal')->extension();
            $request->file('img_principal')->move($destinationPath, $principalName);
            $validatedData['img_principal'] = $principalName;
        }

        Campus::create($validatedData);

        return response()->json(['message' => 'Campus created successfully']);
    }

    public function edit($id)
    {
        $campus = Campus::findOrFail($id);
        return response()->json($campus);
    }

    public function update(Request $request, $id)
    {
        $campus = Campus::findOrFail($id);

        $validatedData = $request->validate([
            'catcampus_id' => 'required|exists:campus_area,id',
            'title' => 'required|string|max:255',
            'NPSN' => 'nullable|string|max:255',
            'school_tour' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:campuses,slug,' . $id,
            'img_header' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'from' => 'nullable|string|max:255',
            'from_id' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'message_id' => 'nullable|string',
            'address' => 'nullable|string',
            'maplocation' => 'nullable|string',
            'content' => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);

        $destinationPath = public_path('images/campuses');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        if ($request->hasFile('img_header')) {
            if (!empty($campus->img_header)) {
                $oldHeaderPath = $destinationPath . DIRECTORY_SEPARATOR . $campus->img_header;
                if (File::exists($oldHeaderPath)) {
                    File::delete($oldHeaderPath);
                }
            }

            $headerName = 'header_' . time() . '_' . uniqid() . '.' . $request->file('img_header')->extension();
            $request->file('img_header')->move($destinationPath, $headerName);
            $validatedData['img_header'] = $headerName;
        }

        if ($request->hasFile('img_principal')) {
            if (!empty($campus->img_principal)) {
                $oldPrincipalPath = $destinationPath . DIRECTORY_SEPARATOR . $campus->img_principal;
                if (File::exists($oldPrincipalPath)) {
                    File::delete($oldPrincipalPath);
                }
            }

            $principalName = 'principal_' . time() . '_' . uniqid() . '.' . $request->file('img_principal')->extension();
            $request->file('img_principal')->move($destinationPath, $principalName);
            $validatedData['img_principal'] = $principalName;
        }

        $campus->update($validatedData);

        return response()->json(['message' => 'Campus updated successfully']);
    }

    public function destroy($id)
    {
        $campus = Campus::findOrFail($id);

        $destinationPath = public_path('images/campuses');
        if (!empty($campus->img_header)) {
            $headerPath = $destinationPath . DIRECTORY_SEPARATOR . $campus->img_header;
            if (File::exists($headerPath)) {
                File::delete($headerPath);
            }
        }

        if (!empty($campus->img_principal)) {
            $principalPath = $destinationPath . DIRECTORY_SEPARATOR . $campus->img_principal;
            if (File::exists($principalPath)) {
                File::delete($principalPath);
            }
        }

        $campus->delete();

        return response()->json(['message' => 'Campus deleted successfully']);
    }
}
