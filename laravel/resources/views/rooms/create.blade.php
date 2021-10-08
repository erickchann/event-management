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
        <div class="border-bottom mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h1 class="h2">{{ $event->name }}</h1>
            </div>
            <span class="h6">{{ $event->date }}</span>
        </div>

        <div class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Create new room</h2>
            </div>
        </div>

        <form class="needs-validation" novalidate action="/events/{{ $event->id }}/rooms" method="POST">
            @csrf

            <div class="row">
                <div class="col-12 col-lg-4 mb-3">
                    <label for="inputName">Name</label>
                    <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}" id="inputName" name="name" placeholder="" value="{{ old('name') }}">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-4 mb-3">
                    <label for="selectChannel">Channel</label>
                    <select class="form-control {{ $errors->has('channel') ? 'is-invalid' : ''}}" id="selectChannel" name="channel">
                        @foreach ($event->channels as $channel)
                            <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                        @endforeach
                    </select>

                    @error('channel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-4 mb-3">
                    <label for="inputCapacity">Capacity</label>
                    <input type="number" class="form-control {{ $errors->has('capacity') ? 'is-invalid' : ''}}" id="inputCapacity" name="capacity" placeholder="" value="{{ old('capacity') }}">

                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary" type="submit">Save room</button>
            <a href="/events/{{ $event->id }}" class="btn btn-link">Cancel</a>
        </form>
    </main>
@endsection