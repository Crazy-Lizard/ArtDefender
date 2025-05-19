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
        // return view('profile');
        $user = User::findOrFail($id);
        
        $approvedArts = $user->arts()
            ->where('request_status', 'approved')
            ->get();

        $waitingArts = $user->arts()
            ->where('request_status', 'waiting')
            ->get();

        $rejectedArts = $user->arts()
            ->where('request_status', 'rejected')
            ->get();

        return view('profile', compact(
            'user',
            'approvedArts',
            'waitingArts',
            'rejectedArts'
        ));
    }

}
