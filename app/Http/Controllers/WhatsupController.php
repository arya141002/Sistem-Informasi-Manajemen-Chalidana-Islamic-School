<?php

namespace App\Http\Controllers;

use App\Models\Whatsup;
use App\Models\WhatsupCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class WhatsupController extends Controller
{
    public function index()
    {
        $title = 'Whatsup';
        $categories = WhatsupCategory::query()->select('id', 'title')->orderBy('title')->get();
        return view('admin.whatsup', compact('title', 'categories'));
    }

    public function data()
    {
        $query = Whatsup::with('category:id,title')->latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<button class="btn btn-sm btn-primary" onclick="editWhatsup(' . $item->id . ')"><i class="bx bx-edit"></i></button> '
                    . '<button class="btn btn-sm btn-danger" onclick="deleteWhatsup(' . $item->id . ')"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['action', 'content', 'content_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'news_id'    => 'required|exists:whatsup_category,id',
            'tanggal'    => 'nullable|date',
            // 'slug' dihapus dari validasi request
            'img'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'      => 'required|string|max:255',
            'title_id'   => 'required|string|max:255',
            'content'    => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);
    
        $data = $request->only(['news_id', 'tanggal', 'title', 'title_id', 'content', 'content_id']);
    
        // Generate slug otomatis dari title
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
    
        // Cek keunikan slug di tabel news (atau sesuaikan dengan model Whatsup)
        while (Whatsup::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
    
        $data['slug'] = $slug;
    
        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/whatsup');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }
    
        Whatsup::create($data);
    
        return response()->json(['message' => 'Whatsup created successfully']);
    }

    public function edit($id)
    {
        $item = Whatsup::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Whatsup::findOrFail($id);

        $request->validate([
            'news_id' => 'required|exists:whatsup_category,id',
            'tanggal' => 'nullable|date',
            'slug' => 'required|string|max:200|unique:news,slug,' . $id,
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',
            'content' => 'nullable|string',
            'content_id' => 'nullable|string',
        ]);

        $data = $request->only(['news_id', 'tanggal', 'slug', 'title', 'title_id', 'content', 'content_id']);

        if ($request->hasFile('img')) {
            $destinationPath = public_path('images/whatsup');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!empty($item->img)) {
                $oldImagePath = $destinationPath . DIRECTORY_SEPARATOR . $item->img;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('img')->extension();
            $request->file('img')->move($destinationPath, $imageName);
            $data['img'] = $imageName;
        }

        $item->update($data);
        return response()->json(['message' => 'Whatsup updated successfully']);
    }

    public function destroy($id)
    {
        $item = Whatsup::findOrFail($id);
        if (!empty($item->img)) {
            $imagePath = public_path('images/whatsup') . DIRECTORY_SEPARATOR . $item->img;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $item->delete();
        return response()->json(['message' => 'Whatsup deleted successfully']);
    }
}
