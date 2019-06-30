<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel To-do List Application</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/laravel-todolist/node_modules/materialize-css/dist/css/materialize.min.css">
</head>

<body>
    <div class="container">

        <!-- Login info & Logout button -->
        <section id="user-info">
            <div class="row">
                <div class="col m10">
                    <p>Logged in as <b>{{ Auth::user()->name }}</b></p>
                </div>
                <div class="col m2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <p>
                            <button type="submit" class="waves-effect waves-light btn">
                                Logout
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </section>


        <!-- Invitation List -->
        
        @isAdmin
            @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
            <section id="invitation-list">
                <div class="row">
                    <div class="col s12">
                        <ul class="collapsible">
                            <li>
                                <div class="collapsible-header">
                                    <i class="material-icons">person_add</i>
                                    Invitations
                                    <span class="new badge red">{{ $pendingInvitations->count() }}</span>
                                </div>
                                <div class="collapsible-body">
                                    @foreach($pendingInvitations as $invitation)
                                    <p>
                                        <span class="green-text"><b>{{ $invitation->worker->name }}</b></span>
                                        <a href="{{ route('acceptInvitation', ['id' => $invitation->id]) }}">Accept</a> | <a href="{{ route('declineInvitation', ['id' => $invitation->id]) }}">Decline</a>
                                    </p>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            @endif
        @endisAdmin

        <!-- App Name Heading -->
        <div class="row">
            <div class="col s12">
                <h1 class="center-align teal-text text-darken-2">To-do List</h1>
            </div>
        </div>

        @yield('content')

    </div>

    <script src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/laravel-todolist/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/laravel-todolist/node_modules/materialize-css/dist/js/materialize.min.js"></script>

    <!-- Initialize Materialie Collapsible element(s) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var collapsibleElems = document.querySelectorAll('.collapsible');
            var options = {};
            var CollapsibleInstances = M.Collapsible.init(collapsibleElems, options);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var selectInputs = document.querySelectorAll('select');
            var selectInstances = M.FormSelect.init(selectInputs);
        });
    </script>
</body>

</html>