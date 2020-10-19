<div class="card mt-3">
	<ul class="text-sm font-semibold text-default list-reset">
		@forelse ($project->activity as $activity)
			<li class="{{ $loop->last ? '' : 'mb-1' }}">
				@include("projects.activity.{$activity->description}")
				<span class="text-xs text-muted">{{ $activity->created_at->diffForHumans(null, true) }}</span>
			</li>
		@empty
			No activity yet.
		@endforelse
	</ul>
</div>