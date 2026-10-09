<?php

namespace App\Http\Controllers;

use App\Models\FreelancerProfile;
use App\Models\HireRequest;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /** Employers only (route middleware). Writing again edits the earlier review. */
    public function store(Request $request, FreelancerProfile $freelancer)
    {
        // reviews are only for employers who finished a job with this freelancer
        $hasCompletedJob = HireRequest::where('employer_id', $request->user()->id)
            ->where('freelancer_profile_id', $freelancer->id)
            ->where('status', HireRequest::COMPLETED)
            ->exists();

        abort_unless($hasCompletedJob, 403, 'You can review a freelancer after a hire request with them is marked completed.');

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
