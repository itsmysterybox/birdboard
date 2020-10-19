<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase; // Traits
    // RefreshDatabase helps to reset everything back to the initial state after test
    // Use it when database is manipulated somehow while testing

    // Either add `test` before test name function or use @test annotation as below so that phpunit recognize it as test
    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    // /** @test */
    // public function guests_cannot_create_projects()
    // {
    //     // $this->withoutExceptionHandling();
    //     $attributes = factory('App\Project')->raw();
    //     $this->post('/projects', $attributes)->assertRedirect('login');
    //     // $this->post('/projects', $attributes)->assertSessionHasErrors('owner_id');
    // }

    // /** @test */
    // public function guests_cannot_view_projects()
    // {
    //     $this->get('/projects')->assertRedirect('login');
    // }

    // /** @test */
    // public function guests_cannot_view_a_single_project()
    // {
    //     $project = factory('App\Project')->create();
    //     $this->get($project->path())->assertRedirect('login');
    // }

    /** @test */
    public function a_user_can_create_a_project()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        // $attributes = [
        //     'title' => $this->faker->sentence,
        //     'description' => $this->faker->sentence,
        //     'notes' => 'General notes here.',
        // ];

        $attributes = factory(Project::class)->raw();
        
        // $this->post('/projects', $attributes)->assertRedirect('/projects'); // After creating a project, it redirects back to /projects/.
        // When new project is created, POST request is sent through /projects route with the attributes (request)

        $response = $this->followingRedirects()->post('/projects', $attributes);
        // $project = Project::where($attributes)->first();
        // Above line is same as below
        // $this->assertDatabaseHas('projects', $attributes);
        // $response->assertRedirect($project->path());

        // $this->assertDatabaseHas('projects', $attributes);
        // Assert that database has `projects` table with the defined attributes

        // $this->get($project->path())
        $response
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function tasks_can_be_included_as_part_of_a_new_project_creation()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw();

        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body' => 'Task 2'],
        ];

        $this->post('/projects', $attributes);
        $this->assertCount(2, Project::first()->tasks);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        // $project->exists()...
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        // $this->withoutExceptionHandling();
        // $this->actingAs(factory('App\User')->create());
        // $this->signIn();

        // $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['title' => 'changed', 'description' => 'changed', 'notes' => 'changed'])
            ->assertRedirect($project->path());

        // $this->get($project->path() . '/edit')->assertStatus(200);
        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes() {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'changed']);

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        // $this->signIn();
        // $this->be(factory('App\User')->create());

        // $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $project = ProjectFactory::create();

        // $this->get('/projects/' . $project->id) // either use named route or add path method to model
        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();
        // $this->be(factory('App\User')->create());
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        // $this->get('/projects/' . $project->id) // either use named route or add path method to model
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        // $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        // $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
