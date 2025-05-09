<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Art; 

class ArtController extends Controller
{
    //
    public function create(Request $request)
    {
        return view('arts.create', [
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            // 'image_path' => 'required|string|max:255', 
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:1000',
            'creator' => 'nullable|string|max:255',
            'art_type' => 'required|in:street-art,mural,tag,sticker',
            'art_status' => 'required|in:live,buffed,unknown',
            'art_created_year' => 'nullable|integer|min:1900|max:'.date('Y'),
        ]);

        $imagePath = $request->file('image')->store('arts', 'public');

        Art::create([
            'user_id' => auth()->user()->id,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'image_path' => $imagePath,
            'description' => $validated['description'],
            'creator' => $validated['creator'],
            'art_type' => $validated['art_type'],
            'art_status' => $validated['art_status'],
            'art_created_year' => $validated['art_created_year'],
            'request_status' => 'waiting', // Статус по умолчанию
        ]);

        return redirect()->route('map')->with('success', 'Art submitted successfully!');
    }

    public function moderate() {
        if (!auth()->user()->IsModerator()) {
            return redirect()->route('map');
        }
        $arts = Art::where('request_status', 'waiting')->get();
        return view('moderate', ['arts' => $arts]);
    }
}
