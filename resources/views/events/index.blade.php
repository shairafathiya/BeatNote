@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-3">🎉 Upcoming Music Events</h2>
    <p class="text-center text-muted mb-4">Explore and join exciting music events. You can also create your own!</p>

    <div class="text-end mb-4">
        <a href="{{ route('events.create') }}" class="btn btn-primary">+ Create Event</a>
    </div>

    <div class="row">
        @forelse ($events as $event)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }} | {{ $event->time }}
                    </h6>
                    <p class="card-text"><strong>Location:</strong> {{ $event->location }}</p>
                    <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-secondary btn-sm">View Details</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">No events available yet. Be the first to create one!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
