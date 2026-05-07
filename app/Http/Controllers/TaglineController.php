<?php

namespace App\Http\Controllers;
use App\Models\Tagline;

use Illuminate\Http\Request;

class TaglineController extends Controller
{
    public function index()
    {
        $title = 'Tagline Management';
        $tagline = Tagline::first();
        return view('admin/tagline', compact('title', 'tagline'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'jargon' => 'required|string|max:255',
            'jargon_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $tagline = Tagline::first();
        if ($tagline) {
            $tagline->update($data);
        } else {
            Tagline::create($data);
        }

        return response()->json(['message' => 'Tagline updated successfully']);
    }
}
