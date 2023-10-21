@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Meetings') }}</div>

                    <div class="card-body">
                      <table class="table table-bordered">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>Subject</th>
                              <th>Created By</th>
                              <th>Attendees</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($meetings as $meeting)
                              <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $meeting->subject }}</td>
                                  <td></td>
                                  <td>Attendees</td>
                                  <td>Action</td>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
