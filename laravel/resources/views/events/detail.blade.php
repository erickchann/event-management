@extends('layouts.app')

@section('content')
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/events">Manage Events</a></li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>{{ $event->name }}</span>
            </h6>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="/events/{{ $event->id }}">Overview</a></li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Reports</span>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item"><a class="nav-link" href="/events/{{ $event->id }}/reports">Room capacity</a></li>
            </ul>
        </div>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

        @if (session('status'))
            <div class="alert alert-success mt-5">{{ session('status') }}</div>
        @endif

        <div class="border-bottom mb-3 pt-3 pb-2 event-title">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h1 class="h2">{{ $event->name }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="/events/{{ $event->id }}/edit" class="btn btn-sm btn-outline-secondary">Edit event</a>
                    </div>
                </div>
            </div>
            <span class="h6">{{ $event->date }}</span>
        </div>

        <!-- Tickets -->
        <div id="tickets" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Tickets</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="/events/{{ $event->id }}/tickets/create" class="btn btn-sm btn-outline-secondary">
                            Create new ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row tickets">
            @foreach ($event->tickets as $ticket)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ticket->name }}</h5>
                            <p class="card-text">{{ $ticket->cost }}.-</p>
                            <p class="card-text">&nbsp;</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sessions -->
        <div id="sessions" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Sessions</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="/events/{{ $event->id }}/sessions/create" class="btn btn-sm btn-outline-secondary">
                            Create new session
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive sessions">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th class="w-100">Title</th>
                        <th>Speaker</th>
                        <th>Channel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event->rooms as $room)
                        @foreach ($room->sessions as $session)
                            <tr>
                                <td class="text-nowrap">{{ $session->start }} - {{ $session->end }}</td>
                                <td>{{ $session->type }}</td>
                                <td><a href="/events/{{ $event->id }}/sessions/{{ $session->id }}/edit">{{ $session->title }}</a></td>
                                <td class="text-nowrap">{{ $session->speaker }}</td>
                                <td class="text-nowrap">{{ $session->room->name }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Channels -->
        <div id="channels" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Channels</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="/events/{{ $event->id }}/channels/create" class="btn btn-sm btn-outline-secondary">
                            Create new channel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row channels">
            @foreach ($event->channels as $channel)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $channel->name }}</h5>
                            <p class="card-text">{{ $channel->sessions->count() }} sessions, {{ $channel->rooms->count() }} room</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Rooms -->
        <div id="rooms" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Rooms</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="/events/{{ $event->id }}/rooms/create" class="btn btn-sm btn-outline-secondary">
                            Create new room
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive rooms">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event->rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->capacity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection