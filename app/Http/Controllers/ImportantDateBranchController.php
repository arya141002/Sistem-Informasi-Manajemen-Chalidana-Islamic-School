<?php

namespace App\Http\Controllers;

use App\Models\ImportantDateBranch;
use Illuminate\Http\Request;

class ImportantDateBranchController extends Controller
{
    public function index()
    {
        $title = 'Important Date Branches';
        return view('admin.important_date_branches', compact('title'));
    }

    public function data()
    {
        $query = ImportantDateBranch::query()->orderBy('program')->orderBy('sort_order')->orderBy('name');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editBranch(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteBranch(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'program' => 'required|in:primary,preschool',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ImportantDateBranch::create([
            'name' => $request->name,
            'program' => $request->program,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json(['message' => 'Branch created successfully']);
    }

    public function edit($id)
    {
        $item = ImportantDateBranch::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'program' => 'required|in:primary,preschool',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $item = ImportantDateBranch::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'program' => $request->program,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json(['message' => 'Branch updated successfully']);
    }

    public function destroy($id)
    {
        $item = ImportantDateBranch::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Branch deleted successfully']);
    }
}
