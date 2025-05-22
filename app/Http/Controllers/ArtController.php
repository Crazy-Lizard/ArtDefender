<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Art;

class ArtController extends Controller
{
    //
    public function show(Art $art) {
        $currentUrl = url()->current();
        $previousUrl = url()->previous();

        // Store the previous URL if it's different from the current art URL
        if ($previousUrl !== $currentUrl) {
            session()->put('art_show_referrer', $previousUrl);
        }

        return view('arts.art-screen', ['art' => $art]);
    }

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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:1000',
            'creator' => 'nullable|string|max:255',
            'art_type' => 'required|in:street-art,mural,tag,sticker',
            'art_status' => 'required|in:LIVE,BUFFED,UNKNOWN',
            'art_created_year' => 'nullable|integer|min:1900|max:'.date('Y'),
        ]);

        $imagePath = $request->file('image')->store('arts', 'public');

        Art::create([
            'user_id' => auth()->user()->id,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'image_path' => $imagePath,
            'description' => $validated['description'],
            'creator' => $validated['creator'] ?? 'UNKNOWN',
            'art_type' => $validated['art_type'],
            'art_status' => $validated['art_status'],
            'art_created_year' => $validated['art_created_year'],
            'request_status' => 'waiting', // Статус по умолчанию
        ]);

        return redirect()->route('map')->with('success', 'Art submitted successfully!');
    }

    public function showRequests() {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $arts = Art::where('request_status', 'waiting')->get();
        return view('requests', ['arts' => $arts]);
    }

    public function artModerate(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        return view('arts.moderate', ['art' => $art]);
    }

    public function artApprove(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $art->update(['request_status' => 'approved']);
        return redirect()->route('requests')->with('success', 'Art approved');
    }

    public function artReject(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $art->update(['request_status' => 'rejected']);
        return redirect()->route('requests')->with('success', 'Art rejected');
    }

    public function destroy(Art $art)
    {
        if (($art->user_id !== auth()->id()) || (!auth()->user()->isModerator())) {
            abort(403, 'Недостаточно прав');
        }

        $art->delete();

        $redirectUrl = session()->pull('art_show_referrer', route('map'));

        return redirect()->to($redirectUrl);
    }
}
