<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $title = 'Social Media';
        return view('admin.social_media', compact('title'));
    }

    public function data()
    {
        $query = SocialMedia::query()->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editSocialMedia(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteSocialMedia(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'bgcolor' => 'required|string|max:100',
            'link' => 'required|url',
        ]);

        SocialMedia::create($request->only(['title', 'icon', 'bgcolor', 'link']));

        return response()->json(['message' => 'Social media created successfully']);
    }

    public function edit($id)
    {
        $item = SocialMedia::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'bgcolor' => 'required|string|max:100',
            'link' => 'required|url',
        ]);

        $item = SocialMedia::findOrFail($id);
        $item->update($request->only(['title', 'icon', 'bgcolor', 'link']));

        return response()->json(['message' => 'Social media updated successfully']);
    }

    public function destroy($id)
    {
        $item = SocialMedia::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Social media deleted successfully']);
    }
}
