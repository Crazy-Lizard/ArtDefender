<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Art;

class ProfileController extends Controller
{
    //
    public function index($id) {
        $user = User::findOrFail($id);

        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/arts/')) {
            session(['from_art' => true]);
        } else {
            session()->forget('from_art');
        }

        // Для прямых заходов устанавливаем реферер по умолчанию
        if (!session()->has('profile_referrer')) {
            session(['profile_referrer' => route('map')]);
        }
        
        $approvedArts = $user->arts()
            ->where('request_status', 'approved')
            ->get();

        $pendingArts = $user->arts()
            ->where('request_status', 'pending')
            ->get();

        $rejectedArts = $user->arts()
            ->where('request_status', 'rejected')
            ->get();

        return view('profile', compact(
            'user',
            'approvedArts',
            'pendingArts',
            'rejectedArts'
        ));
    }

}
