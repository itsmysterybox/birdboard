<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use App\Http\Requests\ProjectInvitationRequest;
use Illuminate\Http\Request;

class ProjectInvitationsController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {
    	// Using form request here
    	// $this->authorize('update', $project);

    	// request()->validate([
    	// 	'email' => ['required', 'exists:users,email']
    	// ], [
    	// 	'email.exists' => 'The user you are inviting must have a Birdboard account.'
    	// ]);

    	$user = User::whereEmail(request('email'))->first();

    	$project->invite($user);

    	return redirect($project->path());
    }
}
