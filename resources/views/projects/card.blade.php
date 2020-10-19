<div class="flex flex-col card" style="height: 200px;">
	<h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-accent pl-4">
		<a href="{{ $project->path() }}" class="text-default no-underline ">{{ $project->title }}</a>
	</h3>
	<div class="text-default flex-1">{{ str_limit($project->description, 100) }}</div>

	@can ('manage', $project)
		<footer class="text-right">
			<form method="POST" action="{{ $project->path() }}">
				@method('DELETE')
				@csrf
				<button type="submit" class="button text-xs px-2 py-1 rounded">Delete</button>
			</form>
		</footer>
	@endcan
</div>