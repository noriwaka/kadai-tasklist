@extends('layouts.app')

@section('content')
    <div class="prose hero bg-base-200 mx-auto max-w-full rounded">
        <div class="hero-content text-center my-10">
            <div class="max-w-md mb-10">
                <h2>Welcome to the Tasklist</h2>
@include('tasks.index')
            </div>
        </div>
    </div>
@endsection