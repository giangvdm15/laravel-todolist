@extends('layouts.master')

@section('content')

<section id="edit-task">
    <form action="{{ route('update', ['id'=>$task->id]) }}" method="POST" class="col m12">
        @csrf

        <h2>Edit Task</h2>
        <div class="row">
            <div class="input-field col s12">
                <input id="task-name" name="task-name" type="text" class="validate" value="{{ $task->name }}">
                <label for="task-name">Task Name</label>
            </div>
        </div>

        @include('partials.coworkers')

        <div class="row">
            <button type="submit" class="waves-effect waves-light btn">Edit</button>
        </div>
    </form>
</section>
@endsection