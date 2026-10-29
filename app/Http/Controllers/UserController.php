<?php

namespace App\Http\Controllers;
use App\Models\Campus;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function allowedRoleNames(): array
    {
        return [
            'super_admin',
            'managing_director',
            'administration_staff',
            'teacher',
            'principal',
            'administrator',
        ];
    }

    public function index()
    {
        $title = 'User Management';
        $roles = Role::whereIn('name', $this->allowedRoleNames())->get();
        $campuses = Campus::query()->select('id', 'title')->orderBy('title')->get();
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'User Management', 'url' => route('user.index')],
        ];
        return view('admin.user', compact('title', 'breadcrumbs', 'roles', 'campuses'));
    }

    public function data()
    {
        $query = User::with(['role', 'campus'])
            ->whereHas('role', function ($roleQuery) {
                $roleQuery->whereIn('name', $this->allowedRoleNames());
            })
            ->select('users.*');
        return datatables()->of($query)
            ->addColumn('role_name', function (User $user) {
                return $user->role ? $user->role->display_name : '-';
            })
            ->addColumn('campus_name', function (User $user) {
                return $user->campus ? $user->campus->title : 'All campuses';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-inline-block">
                        <button class="btn btn-sm btn-icon edit-record" onclick="changePassword(' . $row->id . ')"><i class="bx bx-key"></i></button>
                        <button class="btn btn-sm btn-icon edit-record" onclick="editUser(' . $row->id . ')"><i class="bx bx-edit"></i></button>
                        <button class="btn btn-sm btn-icon delete-record" onclick="deleteUser(' . $row->id . ')"><i class="bx bx-trash"></i></button>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->whereIn('name', $this->allowedRoleNames());
                }),
            ],
            'campus_id' => [
                'nullable',
                'exists:campuses,id',
                Rule::requiredIf(function () use ($request) {
                    return Role::whereKey($request->role_id)
                        ->where('name', 'administration_staff')
                        ->exists();
                }),
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id,
            'campus_id' => $request->campus_id,
        ]);

        return response()->json(['message' => 'User berhasil disimpan!']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->whereIn('name', $this->allowedRoleNames());
                }),
            ],
            'campus_id' => [
                'nullable',
                'exists:campuses,id',
                Rule::requiredIf(function () use ($request) {
                    return Role::whereKey($request->role_id)
                        ->where('name', 'administration_staff')
                        ->exists();
                }),
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'campus_id' => $request->campus_id,
        ]);

        return response()->json(['message' => 'User berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus!']);
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => $request->password,
        ]);

        return response()->json(['message' => 'Password berhasil diperbarui!']);
    }
}
