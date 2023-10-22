@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h3>{{ __('Meetings') }}</h3>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#meetingModal">
                                Create Meeting
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date/Time</th>
                                <th>Subject</th>
                                <th>Created By</th>
                                <th>Attendees</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($meetings as $meeting)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ date("M j, Y",strtotime($meeting->date)) }} {{ date("h:i A",strtotime($meeting->time)) }}</td>
                                    <td>{{ $meeting->subject }}</td>
                                    <td>{{ $meeting->createdBy->name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($meeting->attendees as $attendee)
                                                <li>{{ $attendee->user->email }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-sm btn-link edit_meeting"
                                           data-id="{{$meeting->id}}"><span class="text-primary">Edit</span></a>
                                        <a type="button" class="btn btn-sm btn-link delete_meeting"
                                           data-id="{{$meeting->id}}"><span class="text-danger">Delete</span></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No Meeting Available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                {{ $meetings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Meeting Modal -->
    <div class="modal fade" id="meetingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add_meeting_form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Subject:</label>
                                <input type="text" class="form-control" name="subject">
                                <span class="text-danger error" id="subject_error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Meeting Date:</label>
                                <input type="date" class="form-control" name="date"
                                       value="{{ now()->format('Y-m-d') }}">
                                <span class="text-danger error" id="date_error"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="">Meeting Time:</label>
                                <input type="time" class="form-control" name="time" value="{{ now()->format('H:i') }}">
                                <span class="text-danger error" id="time_error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">Attendees:</label>
                                <select name="attendees[]" id="attendees" class="form-control" multiple>
                                    <option value="">----Select Attendees----</option>
                                    @forelse($attendees as $attendee)
                                        <option value="{{ $attendee->id }}">{{ $attendee->email }}</option>
                                    @empty
                                        <option value="">No Attendee Available</option>
                                    @endforelse
                                </select>
                                <span class="text-danger error" id="attendees_error"></span>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm" id="submit_btn">Save Meeting</button>
                        @csrf
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Update Meeting Modal -->
    <div class="modal fade" id="editMeetingModal">
        <div class="modal-dialog">
            <div class="modal-content" id="editMeetingData">
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('meetings.js')
@endsection
