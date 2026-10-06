<?php

namespace App\Http\Controllers;

use App\Models\FreelancerProfile;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /** Employers only (route middleware). Writing again edits the earlier review. */
    public function store(Request $request, FreelancerProfile $freelancer)
    {
        $data = $request->validate([
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rating.required' => 'Please choose a star rating.',
            'rating.between'  => 'Please choose a star rating.',
            'comment.required' => 'Please write a short review.',
            'comment.min'      => 'Please write at least 10 characters.',
        ]);

        Review::updateOrCreate(
            ['freelancer_profile_id' => $freelancer->id, 'user_id' => $request->user()->id],
            $data
        );

        return redirect()->route('freelancers.show', $freelancer)
            ->with('success', 'Thanks! Your review has been saved.');
    }
}
