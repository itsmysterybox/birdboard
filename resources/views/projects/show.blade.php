@extends('layouts.app')

@section('content')

	<header class="flex items-center mb-3 py-4">
		<div class="flex justify-between items-end w-full">
			<p class="text-accent text-base font-normal">
				<a href="/projects" class="text-accent text-base font-normal no-underline">My Projects</a> / {{ $project->title }}
			</p>
			<div class="flex items-center">
				@foreach ($project->members as $member)
					<img class="mr-2 rounded-full w-10 text-default" src="{{ gravatar_url($member->email) }}" alt="{{ $member->name }}'s avatar" title="{{ $member->name }}">
				@endforeach
				<img class="mr-2 rounded-full w-10 text-default" src="{{ gravatar_url($project->owner->email) }}" alt="{{ $project->owner->name }}'s avatar" title="{{ $project->owner->name }}">
				<a href="{{ $project->path() . '/edit' }}" class="button ml-6">Edit Project</a>
			</div>
		</div>
	</header>

	<main>
		<div class="lg:flex -mx-3">
			<div class="lg:w-3/4 px-3 mb-6">
				<div class="mb-8">
					<h2 class="text-lg text-default font-normal mb-3">Tasks</h2>
					{{-- tasks --}}
					
					@forelse ($project->tasks as $task)
						<div class="card mb-3">
							<form method="POST" action="{{ $task->path() }}" class="mx-2">
								@method('PATCH')
								@csrf

								<div class="flex items-center -p-2">
									<input name="body" value="{{ $task->body }}" class="bg-card w-full mr-2 p-2 outline-none focus:shadow-outline rounded font-semibold text-default {{ $task->completed ? 'text-default font-semibold line-through' : '' }}">
									<input name="completed" type="checkbox" class="form-checkbox form-checkbox-dark" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
								</div>

							</form>
						</div>
					@empty
						<p class="text-default">No tasks yet!</p>
					@endforelse

					<!-- <div class="border-b-2 shadow mt-5 border-red-500"> -->
					<div class="card mb-3">
						<form method="POST" action="{{ $project->path() . '/tasks' }}">
							@csrf
							<input type="text" placeholder="Add new task..." name="body" class="bg-card w-full p-2 outline-none focus:shadow-outline rounded">
						</form>
					</div>

				</div>

				<div>
					<h2 class="text-lg text-default font-normal mb-3">General Notes</h2>
					{{-- general notes --}}

					<form method="POST" action="{{ $project->path() }}">
						@csrf
						@method('PATCH')
						<textarea name="notes" class="card w-full mb-4" style="min-height: 200px;" placeholder="Anything special that you want to make a note of?">{{ $project->notes }}</textarea>

						@if ($errors->any())
				            @foreach ($errors->all() as $error)
				            	<p class="text-sm text-red-900 mb-2" style="color: red;">{{ $error }}</p>
				            @endforeach
						@endif
						<button type="submit" class="button">Save</button>
					</form>
				</div>
			</div>
			<div class="lg:w-1/4 px-3">
				@include('projects.card')

				@include('projects.activity.card')

				@can ('manage', $project)
					@include('projects.invite')
				@endcan
			</div>
		</div>
	</main>

@endsection