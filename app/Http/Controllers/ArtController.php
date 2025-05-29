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

        // Всегда сохраняем реферер для страницы арта
        if ($previousUrl && $previousUrl !== $currentUrl) {
            session(['art_show_referrer' => $previousUrl]);
        }

        return view('arts.art-screen', [
            'art' => $art,
            'editable' => auth()->check() && (auth()->id() === $art->user_id || auth()->user()->isModerator())
        ]);
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
            'request_status' => 'pending', // Статус по умолчанию
        ]);

        return redirect()->route('map')->with('success', 'Art submitted successfully!');
    }

    public function destroy(Art $art)
    {
        if (($art->user_id !== auth()->id()) && (!auth()->user()->isModerator())) {
            abort(403, 'Недостаточно прав');
        }
    
        $art->delete();

        // Очищаем реферер после использования
        $redirectUrl = session()->pull('art_show_referrer', route('map'));
        
        // Очищаем флаг "из арта" при удалении
        session()->forget('from_art');
        
        return redirect()->to($redirectUrl);

    }
    
    public function updateField(Request $request, Art $art)
    {
        $validated = $request->validate([
            'field' => 'required|in:description,art_status,art_type,art_created_year,creator',
            'value' => 'nullable|string|max:255'
        ]);
        
        $field = $validated['field'];
        $art->$field = $validated['value'] ?: null;
        $art->save();
        
        // Обновляем модель, чтобы получить актуальные данные
        $art->refresh();

        return response()->json([
            'success' => true,
            'newValue' => $art->$field,
            'art' => $art->only(['id', 'user_id']),
            'message' => 'Field updated successfully'
        ]);
    }
}
