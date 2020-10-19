<?php 

namespace App;

trait RecordsActivity
{
    public $oldAttributes = [];

    /**
     * Boot the trait.
	*/
    // To call the boot function on a trait, follow convention -> bootTraitName
    public static function bootRecordsActivity()
    {
    	foreach (self::recordableEvents() as $event) {
    		static::$event(function ($model) use ($event) {
    			$model->recordActivity($model->activityDescription($event));
    		});
    		if ($event === "updated") {
    			static::updating(function ($model) {
		    		$model->oldAttributes = $model->getOriginal();
		    	});
    		}
    	}
    }

    protected function activityDescription($description) {
    		return $description = "{$description}_" . strtolower(class_basename($this)); // created_task
    }

    protected static function recordableEvents() {
    	if (isset(static::$recordableEvents)) { // if user has created properties on model, override the defaults
    		return static::$recordableEvents;
    	}
    	// return ['created', 'updated', 'deleted'];
        return ['created', 'updated'];
    }
    
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

	public function recordActivity($description)
	{
	    // $this->activity()->create(compact('description'));
	    $this->activity()->create([
            // 'user_id' => $this->activityOwner()->id,
            'user_id' => ($this->project ?? $this)->owner->id,
	        'description' => $description,
	        'changes' => $this->activityChanges(),
	        'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
	    ]);
	    // Activity::create([
	    //     'project_id' => $this->id,
	    //     'description' => $description,
	    // ]);
	}

    // public function activityOwner()
    // {
    //     // if (auth()->check()) {
    //     //     return auth()->user();
    //     // }

    //     $project = $this->project ?? $this;

    //     return $project->owner;

    //     // if (class_basename($this) === 'Project') {
    //     //     return $this->owner;
    //     // }

    //     // return $this->project->owner;
    // }

    protected function activityChanges() {
        // if ($description === 'updated') {
        if ($this->wasChanged()) {
            return [
                'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                // 'after' => array_diff($this->getAttributes(), $this->oldAttributes),
                'after' => array_except($this->getChanges(), 'updated_at')
            ];
        }
    }
}


?>