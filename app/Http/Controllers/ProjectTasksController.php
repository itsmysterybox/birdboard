<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function index($project)
    {
        return redirect('/projects/' . $project);
    }

    public function store(Project $project)
    {
    	$this->authorize('update', $project);

    	request()->validate(['body' => 'required']);

    	$project->addTask(request('body'));

    	return redirect($project->path());
    }    

    public function update(Project $project, Task $task)
    {
        // if (auth()->user()->isNot($task->project->owner)) {
        //     abort(403);
        // }
        $this->authorize('update', $task->project);

        $attributes = request()->validate(['body' => 'required']);

        $task->update($attributes);

        request('completed') ? $task->complete() : $task->incomplete();

        // if (request('completed')) {
        //     $task->complete();
        // } else {
        //     $task->incomplete();
        // }

        // $task->update([
        //     'body' => request('body'),
        //     'completed' => request()->has('completed'),
        // ]);

        return redirect($project->path());
    }
}
