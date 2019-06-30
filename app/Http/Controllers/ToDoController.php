<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Invitation;
use Illuminate\Support\Facades\Auth;

class ToDoController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            $pendingInvitations = Invitation::where('admin_id', Auth::user()->id)->where('accepted', 0)->get();
            $tasks = Task::where('user_id', Auth::user()->id)->orWhere('admin_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(5);
            $coworkers = Invitation::where('admin_id', Auth::user()->id)->where('accepted', 1)->get();
        }
        else {
            $pendingInvitations = [];
            $tasks = Task::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(5);
            $coworkers = User::where('is_admin', 1)->get();
        }

        return view('index', compact('tasks', 'coworkers', 'pendingInvitations'));
    }

    public function save(Request $req)
    {
        if ($req->input('task-name')) {
            $task = new Task();
            $task->name = $req->input('task-name');

            if (Auth::user()->is_admin) {
                if ($req->input('assign-to') == Auth::user()->id) {
                    Auth::user()->tasks()->save($task);
                }
                elseif ($req->input('assign-to') != null) {
                    $task->user_id = $req->input('assign-to');
                    $task->admin_id = Auth::user()->id;
                    $task->save();
                }
            }
            else {
                Auth::user()->tasks()->save($task);
            }

        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $task = Task::find($id);

        if (Auth::user()->is_admin) {
            $coworkers = Invitation::where('admin_id', Auth::user()->id)->where('accepted', 1)->get();
            $pendingInvitations = Invitation::where('admin_id', Auth::user()->id)->where('accepted', 0)->get();
        }
        else {
            $coworkers = [];
            $pendingInvitations = [];
        }

        // Prevent user from accessing unauthorized tasks by passing params in the URL
        if ($this->_authorize($task->user_id)) {
            return view('edit', ['task' => $task, 'coworkers' => $coworkers, 'invitations' => $pendingInvitations]);
        }
        else {
            return redirect()->back();
        }
    }

    public function update($id, Request $req)
    {
        if ($req->input('task-name')) {
            $task = Task::find($id);
            $task->name = $req->input('task-name');

            if (Auth::user()->is_admin) {
                if ($req->input('assign-to') == Auth::user()->id) {
                    Auth::user()->tasks()->save($task);
                }
                elseif ($req->input('assign-to') != null) {
                    $task->user_id = $req->input('assign-to');
                    $task->admin_id = Auth::user()->id;
                    $task->save();
                }
            }
            else {
                if ($this->_authorize($task->user_id)) {
                    $task->save();
                }
            }
        }
        return redirect('/');
    }
    
    public function delete($id)
    {
        $task = Task::find($id);

        if (!Auth::user()->is_admin && !$this->_authorize($task->user_id)) {
            return redirect()->back();
            exit();
        }

        $task->delete();
        
        return redirect()->back();
    }

    // Method for manually authorizing user to perform certain actions
    // instead of using Laravel built-in
    protected function _authorize($id)
    {
        return Auth::user()->id == $id ? true : false;
    }
    
    public function updateStatus($id)
    {
        $task = Task::find($id);

        if ($this->_authorize($task->user_id)) {
            $task->status = !$task->status;
            $task->save();
        }
        return redirect()->back();
    }

    public function sendInvitation(Request $req)
    {
        if ((int) $req->input('invitation-recipient') > 0
            && !Invitation::where('worker_id', Auth::user()->id)->where('admin_id', $req->input('invitation-recipient'))->exists())
        {
            $invitation = new Invitation;
            $invitation->worker_id = Auth::user()->id;
            $invitation->admin_id = (int) $req->input('invitation-recipient');
            $invitation->save();
        }

        return redirect()->back();
    }

    public function acceptInvitation($id)
    {
        $invitation = Invitation::find($id);

        if ($this->_authorize($invitation->admin_id)) {
            $invitation->accepted = true;
            $invitation->save();
        }
        
        return redirect()->back();
    }

    public function declineInvitation($id)
    {
        $invitation = Invitation::find($id);

        if ($this->_authorize($invitation->admin_id)) {
            $invitation->delete();
        }
        
        return redirect()->back();
    }

    public function removeWorker($id)
    {
        $invitation = Invitation::find($id);

        if ($this->_authorize($invitation->admin_id)) {
            $invitation->delete();
        }
        
        return redirect()->back();
    }
}
