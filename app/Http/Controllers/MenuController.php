<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $title = 'Menu Management';
        $menus = Menu::with('parent')->orderBy('order', 'asc')->get();
        $roles = Role::all();
        return view('admin/menu', compact('title', 'menus', 'roles'));
    }

    public function data(){
        $query = Menu::with('parent'); 
        return DataTables::of($query)
            ->addColumn('parent_title', function($row) {
                return $row->parent ? $row->parent->title : '<span class="badge bg-label-secondary">Main Menu</span>';
            })
            ->addColumn('action', function($row) {
                return '
                    <div class="d-inline-block">
                        <button class="btn btn-sm btn-icon edit-record" onclick="editMenu(' . $row->id . ')"><i class="bx bx-edit"></i></button>
                        <button class="btn btn-sm btn-icon delete-record" onclick="deleteMenu(' . $row->id . ')"><i class="bx bx-trash"></i></button>
                    </div>';
            })
            ->rawColumns(['parent_title', 'action']) 
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $menu = Menu::create([
            'order' => $request->order,
            'icon' => $request->icon,
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'role_access' => $request->roles ? implode(',', $request->roles) : null,
            'is_active' => true
        ]);

        return response()->json(['message' => 'Menu berhasil disimpan!']);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => 'Menu berhasil dihapus!']);
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'order' => $request->order,
            'icon' => $request->icon,
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'role_access' => $request->roles ? implode(',', $request->roles) : null,
        ]);

        return response()->json(['message' => 'Menu berhasil diperbarui!']);
    }
}
