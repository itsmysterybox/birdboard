@csrf

<div class="field mb-6">
	<label for="title" class="label text-sm mb-2 block">Title</label>
	<div class="control">
		<input 
			type="text" 
			class="input bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full" 
			name="title" 
			placeholder="My next awesome project" 
			required
			value="{{ $project->title }}">
		@error('title')
		    <p class="text-sm text-red-900">{{ $message }}</p>
		@enderror
	</div>
</div>

<div class="field mb-6">
	<label for="description" class="label text-sm mb-2 block">Description</label>
	<div class="control">
		<textarea 
		name="description"
		rows="10"
		placeholder="I should start learning piano."
		class="textarea bg-transparent border border-muted-light rounded placeholder-black p-2 text-xs w-full"
		required >{{ $project->description }}</textarea>
		@error('description')
		    <p class="text-sm text-red-900">{{ $message }}</p>
		@enderror
	</div>
</div>

<div class="field">
	<div class="control">
		<button type="submit" class="button is-link mr-2">{{ $buttonText }}</button>
		<a href="{{ $project->path() }}" class="text-default">Cancel</a>
	</div>
</div>

@include('errors')