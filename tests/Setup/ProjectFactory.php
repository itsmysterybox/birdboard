<?php 

namespace Tests\Setup;

use App\Project;
use App\User;
use App\Task;

class ProjectFactory {
	protected $tasksCount = 0;
	protected $user;

	public function withTasks($count)
	{
		$this->tasksCount = $count;

		return $this;
	}

	public function ownedBy($user)
	{
		$this->user = $user;

		return $this;
	}

	public function create()
	{
		$project = factory(Project::class)->create([
			// factory(Project::class)->create()->id
			'owner_id' => $this->user ?: factory(User::class) // ?? or ?:
		]);	

		// 	we can be explicit and use if conditional to create task below only if (`tasksCount` > 0), but below code can handle that
		factory(Task::class, $this->tasksCount)->create([
			// factory(Project::class)->create()->id;
			'project_id' => $project->id
		]);

		return $project;
	}
}

?>