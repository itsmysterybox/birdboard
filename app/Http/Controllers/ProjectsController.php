<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
    	// $projects = auth()->user()->projects()->orderBy('updated_at', 'desc')->get();
        $projects = auth()->user()->accessibleProjects();

    	return view('projects.index', compact('projects'));
    }


    public function show(Project $project)
    {
    	// $project = Project::findOrFail(request('project'));

        // if (auth()->id() !== (int) $project->owner_id) { // use less strict checking with != or cast to int
        //     abort(403);
        // }

        $this->authorize('update', $project); // using ProjectPolicy

    	return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
    	// validate
    	// $this->validateRequest();

        // $attributes['owner_id'] = auth()->id();

        $project = auth()->user()->projects()->create($this->validateRequest());

        if ($tasks = request('tasks')) {
            // foreach (request('tasks') as $task) {
            //     $project->addTask($task['body']);
            // }
            $project->addTasks($tasks);
        }

        if (request()->wantsJson()) {
            return ['message' => $project->path()];
        }

    	// persist
    	// Project::create($attributes);
    	// redirect	
    	return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);
        // if (auth()->user()->isNot($project->owner)) {
        //     abort(403);
        // }
        // $project->update([
        //     'notes' => request('notes')
        // ]);

        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();

        return redirect('/projects');
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required|min:2',
            'description' => 'sometimes|required|min:3',
            'notes' => 'nullable',
        ]);
    }

}
