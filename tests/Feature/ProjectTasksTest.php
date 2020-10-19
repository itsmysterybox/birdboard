<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
// use Tests\Setup\ProjectFactory;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        // $this->be(factory('App\User')->create());
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();
        // $this->be(factory('App\User')->create());
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        // $project = factory('App\Project')->create();
        // $task = $project->addTask('Test task');

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();
        // $this->signIn();
        // $project = factory(Project::class)->raw();
        // $project = auth()->user()->projects()->create($project);
        
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        // $project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)->create(); // create Facade
        // $project = app(ProjectFactory::class)
        //     ->ownedBy($this->signIn())
        //     ->withTasks(1)
        //     ->create();

        // $this->signIn();
        
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        // $task = $project->addTask('Test task');

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            // 'completed' => true,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            // 'completed' => true,
        ]);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => false,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => false,
        ]);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        // $this->signIn();
        // $this->actingAs(factory('App\User')->create());
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
