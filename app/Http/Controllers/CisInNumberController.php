<?php

namespace App\Http\Controllers;

use App\Models\CisInNumber;
use Illuminate\Http\Request;

class CisInNumberController extends Controller
{
    public function index()
    {
        $title = 'CIS In Number';
        return view('admin.cis_in_number', compact('title'));
    }

    public function data()
    {
        $query = CisInNumber::query()->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editCisNumber(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteCisNumber(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:200',
            'title' => 'required|string|max:200',
            'title_id' => 'required|string|max:200',
            'number' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        CisInNumber::create($request->only(['icon', 'title', 'title_id', 'number', 'content']));
        return response()->json(['message' => 'CIS In Number created successfully']);
    }

    public function edit($id)
    {
        $item = CisInNumber::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'icon' => 'required|string|max:200',
            'title' => 'required|string|max:200',
            'title_id' => 'required|string|max:200',
            'number' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $item = CisInNumber::findOrFail($id);
        $item->update($request->only(['icon', 'title', 'title_id', 'number', 'content']));

        return response()->json(['message' => 'CIS In Number updated successfully']);
    }

    public function destroy($id)
    {
        $item = CisInNumber::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'CIS In Number deleted successfully']);
    }
}
