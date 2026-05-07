<?php

namespace App\Http\Controllers;
use App\Models\SchoolHistory;

use Illuminate\Http\Request;

class SchoolHistoryController extends Controller
{
    public function index()
    {
        $schoolHistory = SchoolHistory::first();
        $title = 'School History';
        return view('admin.school_history', compact('schoolHistory', 'title'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'required|string',
            'content_id' => 'required|string',
        ]);

        $schoolHistory = SchoolHistory::query()->first();

        if ($schoolHistory) {
            $schoolHistory->update($validated);
        } else {
            $schoolHistory = SchoolHistory::create($validated);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'School history updated successfully.',
                'data' => $schoolHistory,
            ]);
        }

        return redirect()->route('school-history.index')->with('success', 'School history updated successfully.');
    }
}
