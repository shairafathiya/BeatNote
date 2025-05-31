@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">🎉 Upcoming Music Events</h2>
    <p class="text-center text-muted">Here are the upcoming music-related events. You can also create your own!</p>
    <div class="text-end mb-3">
        <a href="/events/create" class="btn btn-primary">+ Create Event</a>
    </div>

    <div class="row">
        @foreach ($events as $event)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $event->date }} | {{ $event->time }}</h6>
                    <p class="card-text"><strong>Location:</strong> {{ $event->location }}</p>
                    <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    <a href="/events/{{ $event->id }}" class="btn btn-outline-secondary btn-sm">View Details</a>
                    <a href="/events/join/{{ $event->id }}" class="btn btn-success btn-sm">Join Event</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
