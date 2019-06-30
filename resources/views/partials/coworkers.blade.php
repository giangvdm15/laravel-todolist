@isAdmin
<div class="row">
    <div class="input-field col s12">
        <select name="assign-to" id="assign-to">
            <option value="{{ Auth::user()->id }}">Myself: {{ Auth::user()->name }}</option>
            @foreach($coworkers as $coworker)
                @if(isset($task) && $coworker->worker->id == $task->user->id)
                <option selected value="{{ $coworker->worker->id }}">{{ $coworker->worker->name }}</option>
                @else
                <option value="{{ $coworker->worker->id }}">{{ $coworker->worker->name }}</option>
                @endif
            @endforeach
        </select>
        <label for="assign-to">Assign to</label>
    </div>
</div>
@endisAdmin