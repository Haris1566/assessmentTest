<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Models\Attendee;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendees = User::where('id', '!=', auth()->user()->id)->get();
        $meetings = Meeting::with(['createdBy', 'attendees.user'])->latest()->paginate(10);
        return view('meetings.index', compact('meetings', 'attendees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingRequest $request)
    {
        //storing meeting data into database
        $meeting = Meeting::create([
            "subject" => $request->subject,
            "time" => $request->time,
            "date" => $request->date,
            "created_by" => auth()->user()->id
        ]);
        //Saving the Attendees of the meeting
        $attendees = [];
        foreach ($request->attendees as $attendee) {
            $attendees[] = ['user_id' => $attendee, 'meeting_id' => $meeting->id, 'created_at' => now()];
        }
        Attendee::insert($attendees); //bulk insert
        //calling google calender API
        //returning response
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}
