<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Point;
use App\Models\Art;

class MapController extends Controller
{
    //
    public function index()
    {
        $approvedArts = Art::where('request_status', 'approved')->get();
        
        // Формируем массив локаций для карты
        $locations = $approvedArts->map(function ($art) {
            return [
                'id' => $art->id,
                'name' => $art->creator 
                    ? "{$art->art_type} by {$art->creator}" 
                    : ucfirst(str_replace('-', ' ', $art->art_type)),
                'lat' => $art->lat,
                'lng' => $art->lng,
            ];
        })->toArray();

        return view('map', compact('locations'));
    }
    
    public function checkPoint(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        // Поиск существующей точки с точностью до 7 знаков
        $point = Point::firstOrCreate(
            [
                'lat' => round($request->lat, 7),
                'lng' => round($request->lng, 7)
            ]
        );

        return redirect()->route('arts.create', [
            'lat' => $point->lat,
            'lng' => $point->lng
        ]);
    }
}
