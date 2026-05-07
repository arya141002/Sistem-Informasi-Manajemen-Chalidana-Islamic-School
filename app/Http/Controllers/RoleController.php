<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $title = 'Role Management';
        return view('admin/role', compact('title'));
    }

    public function data(){
        $query = Role::query();
        return DataTables::of($query)
            ->addColumn('action', function($row) {
                return '
                    <div class="d-inline-block">
                        <button class="btn btn-sm btn-icon edit-record" onclick="editRole(' . $row->id . ')"><i class="bx bx-edit"></i></button>
                        <button class="btn btn-sm btn-icon delete-record" onclick="deleteRole(' . $row->id . ')"><i class="bx bx-trash"></i></button>
                    </div>';
            })
            ->rawColumns(['action']) 
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        return response()->json(['message' => 'Role berhasil disimpan!']);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        return response()->json(['message' => 'Role berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Role berhasil dihapus!']);
    }
}
