<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id')->latest('updated_at');
        // orderByDesc('updated_at')
        // oldest() 'created_at' is passed as default. in ascending order (asc)
    }

    public function accessibleProjects()
    {
        return Project::where('owner_id', $this->id)
            ->orWhereHas('members', function ($query){
                $query->where('user_id', $this->id);
            })->get();
        // $projectsCreated = $this->projects;

        // $ids = \DB::table('project_members')->where('user_id', $this->id)->pluck('project_id');
        // $projectsSharedWith = Project::find($ids);

        // return $projectsCreated->merge($projectsSharedWith);
    }
}
