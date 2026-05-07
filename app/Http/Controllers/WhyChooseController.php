<?php

namespace App\Http\Controllers;
use App\Models\WhyChoose;

use Illuminate\Http\Request;

class WhyChooseController extends Controller
{
    public function index()
    {
        $whyChoose = WhyChoose::first();
        $title = 'Why Choose Us';
        return view('admin.why_choose', compact('whyChoose', 'title'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $whyChoose = WhyChoose::query()->first();

        if ($whyChoose) {
            $whyChoose->update($validated);
        } else {
            $whyChoose = WhyChoose::create($validated);
        }

        return response()->json([
            'message' => 'Why Choose Us updated successfully',
            'data' => $whyChoose,
        ]);
    }
}
