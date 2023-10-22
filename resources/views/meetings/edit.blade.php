<!-- Modal Header -->
<div class="modal-header">
    <h4 class="modal-title">Update Meeting</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
</div>

<!-- Modal body -->
<form action="" id="update_meeting_form">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="">Subject:</label>
                <input type="text" class="form-control" name="subject" value="{{ $meeting->subject }}">
                <span class="text-danger error" id="u_subject_error"></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <label for="">Meeting Date:</label>
                <input type="date" class="form-control" name="date"
                       value="{{ date('Y-m-d',strtotime($meeting->date))}}">
                <span class="text-danger error" id="u_date_error"></span>
            </div>
            <div class="col-md-6">
                <label for="">Meeting Time:</label>
                <input type="time" class="form-control" name="time" value="{{ date('H:i',strtotime($meeting->time)) }}">
                <span class="text-danger error" id="u_time_error"></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <label for="">Attendees:</label>
                <select name="attendees[]" id="u_attendees" class="form-control" multiple>
                    <option value="">----Select Attendees----</option>
                    @forelse($attendees as $attendee)
                        <option
                            value="{{ $attendee->id }}" @selected(in_array($attendee->id,$meeting->attendees->pluck('user_id')->toArray()))>{{ $attendee->email }}</option>
                    @empty
                        <option value="">No Attendee Available</option>
                    @endforelse
                </select>
                <span class="text-danger error" id="u_attendees_error"></span>

            </div>
        </div>
    </div>
    <!-- Modal footer -->
    <div class="modal-footer">
        @csrf
        <input type="hidden" name="update_id" value="{{ $meeting->id }}">
        <input type="hidden" name="currentPage" value="{{ $currentPage }}">
        <button type="submit" class="btn btn-info btn-sm" id="update_btn">Update Meeting</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
    </div>
</form>
