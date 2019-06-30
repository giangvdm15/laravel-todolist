@extends('layouts.master')

@section('content')

<!-- Task List -->
<section id="task-list">
    <table class="highlight responsive-table">
        <thead>
            <tr>
                <th>Task</th>

                @isAdmin
                <th>Assigned to</th>
                @endisAdmin

                <th>Edit</th>

                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <td>
                    @if (!$task->status)
                    <a href="{{ route('updateStatus', $task->id) }}">{{ $task->name }}</a>
                    @else
                    <a href="{{ route('updateStatus', $task->id) }}" class="grey-text" style="text-decoration: line-through;">{{ $task->name }}</a>
                    @endif
                </td>

                @isAdmin
                <td>{{ $task->user->name }}</td>
                @endisAdmin
                <td>
                    <a class="blue-text" title="Edit this task" href="{{ route('edit', $task->id) }}">
                        <i class="small material-icons">edit</i>
                    </a>
                </td>
                <td>
                    <a class="red-text" title="Delete this task" href="{{ route('delete', $task->id) }}"
                        onclick="return confirm('Do you really want to delete the task?')">
                        <i class="small material-icons">delete</i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tasks->links('vendor.pagination.materialize') }}
</section>

<br>
<hr>

<!-- Add new Task Form -->
<section id="add-task">
    <div class="row">
        <form action="{{ route('save') }}" method="POST" class="col m12">
            @csrf
            <h2>Add new Task</h2>
            <div class="row">
                <div class="input-field col s12">
                    <input id="task-name" name="task-name" type="text" class="validate">
                    <label for="task-name">Task Name</label>
                </div>
            </div>
            
            @include('partials.coworkers')

            <div class="row">
                <button class="waves-effect waves-light btn">Add</button>
            </div>
        </form>
        
    </div>
</section>

<br>
<hr>

@isWorker
<section id="send-invitation">
    <div class="row">
        <form action="{{ route('sendInvitation') }}" method="POST" class="col m12">
            @csrf

            <h2>Send Invitation to other Co-workers</h2>
            <div class="row">
                <div class="input-field col s12">
                    <select id="send-invitation-to" name="invitation-recipient">
                        <option value="" disabled selected>Send invitation to</option>
                        @foreach($coworkers as $coworker)
                        <option value="{{ $coworker->id }}">{{ $coworker->name }}</option>
                        @endforeach
                    </select>
                    <label for="invitation-recipient">Send Invitation to</label>
                    <button type="submit" class="waves-effect waves-light btn">Send Invitation</button>
                </div>
            </div>
        </form>
    </div>
</section>

<br>
<hr>
@endisWorker

<!-- Co-worker List -->
@isAdmin
<section id="coworker-list">
    <div class="row">
        <div class="col s12">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h2>My Co-workers</h2>
                </li>
                @foreach($coworkers as $coworker)
                <li class="collection-item">
                    {{ $coworker->worker->name }}
                    <a href="{{ route('removeWorker', ['id' => $coworker->id]) }}" class="secondary-content red-text">Remove</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endisAdmin

@endsection