<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
