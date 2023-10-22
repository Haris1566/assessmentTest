<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Models\Attendee;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;

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
        $startDateTime = $request->date . ' ' . $request->time;
        $event = Event::create([
            'name' => $request->subject,
            'startDateTime' => Carbon::parse($startDateTime),
            'endDateTime' => Carbon::parse($startDateTime)->addHour(),
        ]);
        //adding attendees to calender event
        $this->addCalendarAttendees($meeting, $event);
        //adding newly created google calendar event id to database so that we can use it to update if needed
        $meeting->update(['google_event_id' => $event->id]);

        //returning response
        return response()->json(['success' => true]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting, $currentPage)
    {
        $attendees = User::where('id', '!=', auth()->user()->id)->get();
        return view('meetings.edit', compact('meeting', 'attendees', 'currentPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingRequest $request)
    {
        //find the meeting
        $meeting = Meeting::with('attendees')->find($request->update_id);
        //Update meeting data into database
        $meeting->update([
            "subject" => $request->subject,
            "time" => $request->time,
            "date" => $request->date,
            "created_by" => auth()->user()->id
        ]);
        //Delete old attendees
        $meeting->attendees()->delete();
        //Saving the Attendees of the meeting
        $attendees = [];
        foreach ($request->attendees as $attendee) {
            $attendees[] = ['user_id' => $attendee, 'meeting_id' => $meeting->id, 'created_at' => now()];
        }
        Attendee::insert($attendees); //bulk insert
        //calling google calender API
        //getting the page number of pagination so that
        // we can redirect on exact same page after deleting.
        $redirectPage = $this->getCurrentPage($request->currentPage);
        return response()->json(['success' => true, 'page' => $redirectPage]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting, $currentPage)
    {

        //deleting  attendees
        $meeting->attendees()->delete();
        $meeting->delete();
        //getting the page number of pagination so that
        // we can redirect on exact same page after deleting.
        $redirectPage = $this->getCurrentPage($currentPage);
        return response()->json(['success' => true, 'page' => $redirectPage]);
    }

    private function getCurrentPage($currentPage)
    {
        $paginator = Meeting::paginate(10, ['id']);
        if ($currentPage <= $paginator->lastPage()) {
            return $currentPage;
        }
        return $paginator->lastPage();
    }

    private function addCalendarAttendees(Meeting $meeting, $event)
    {
        // egar loading meeting relationships
        $meeting->load(['attendees.user']);
        foreach ($meeting->attendees as $attendee) {
            $event->addAttendee([
                'email' => $attendee->user->email,
                'name' => $attendee->user->name,
                'comment' => 'Lorum ipsum',
                'responseStatus' => 'needsAction',
            ]);
        }
    }
}
