<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\UserPremium;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function show($id)
    {
        return view('member.movie-detail');
    }

    public function watch($id)
    {
        $userId = auth()->user()->id;
        $userPremium = UserPremium::where('user_id', $userId)->first();
        if ($userPremium) {
            $endOfSubscription = $userPremium->end_of_subscription;
            $date = Carbon::createFromFormat('Y-m-d', $endOfSubscription);

            $isValidSubscription = $date->greaterThan(now());
            if ($isValidSubscription) {
                return view('member.movie-watching');
            }

        }

        return redirect()->route('pricing');
        // return view('member.movie-watching');
    }
}
