<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Point;

class MapController extends Controller
{
    //
    public function index()
    {
        // Пример данных (можно заменить на данные из БД)
        $locations = [
            [
                'name' => 'Центр города',
                'lat' => 44.6167,
                'lng' => 33.5254,
            ],
            // [
            //     'name' => 'Парк',
            //     'lat' => 55.7622,
            //     'lng' => 37.6155,
            // ],
        ];

        return view('map', compact('locations'));
        // return view('map');
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
