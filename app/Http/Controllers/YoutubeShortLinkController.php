<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\YoutubeShortLink;
use Illuminate\Http\Request;

class YoutubeShortLinkController extends Controller
{
    public function index()
    {
        $title = 'Youtube Short Link';
        $campuses = Campus::query()->select('id', 'title')->orderBy('title')->get();
        return view('admin.youtube_short_link', compact('title', 'campuses'));
    }

    public function data()
    {
        $query = YoutubeShortLink::with('campus:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editYoutubeShort(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteYoutubeShort(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'title' => 'required|string|max:255',
            'youtube_link' => 'required|url|max:255',
        ]);

        YoutubeShortLink::create([
            'campus_id' => $request->campus_id,
            'title' => $request->title,
            'youtube_short' => $request->youtube_link,
        ]);
        return response()->json(['message' => 'Youtube short link created successfully']);
    }

    public function edit($id)
    {
        $item = YoutubeShortLink::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'title' => 'required|string|max:255',
            'youtube_link' => 'required|url|max:255',
        ]);

        $item = YoutubeShortLink::findOrFail($id);
        $item->update([
            'campus_id' => $request->campus_id,
            'title' => $request->title,
            'youtube_short' => $request->youtube_link,
        ]);
        return response()->json(['message' => 'Youtube short link updated successfully']);
    }

    public function destroy($id)
    {
        $item = YoutubeShortLink::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Youtube short link deleted successfully']);
    }
}
