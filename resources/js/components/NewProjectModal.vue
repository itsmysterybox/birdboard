<template>
	<modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">
		<h1 class="font-normal mb-16 text-center text-2xl text-default">Let's Start Something New</h1>
		<form @submit.prevent="submit">
			<div class="flex">
				<div class="flex-1 mr-4">
					<div class="mb-4">
						<label for="title" class="text-sm block mb-2 text-default">Title</label>
						<input type="text" id="title"
							class="border p-2 text-xs block w-full rounded"
							:class="form.errors.title ? 'border-error' : 'border-muted-light'"
							v-model="form.title"
							v-focus>
						<span class="text-xs italic text-error" v-if="form.errors.title" v-text="form.errors.title[0]"></span>
					</div>

					<div class="mb-4">
						<label for="description" class="text-sm block mb-2 text-default">Description</label>
						<textarea id="description"
							class="border border-muted-light p-2 text-xs block w-full rounded"
							:class="form.errors.description ? 'border-error' : 'border-muted-light'"
							style="max-height: 250px; min-height: 50px;" 
							rows="7"
							v-model="form.description"
						></textarea>
						<span class="text-xs italic text-error" v-if="form.errors.description" v-text="form.errors.description[0]"></span>
					</div>
				</div>
				<div class="flex-1 ml-4">
					<div class="mb-4 overflow-y-auto" style="max-height: 200px;">
						<label class="text-sm block mb-2 text-default">Need Some Tasks?</label>
						<input
							type="text"
							class="border border-muted-light mb-2 p-2 text-xs block w-full rounded taskInput"
							placeholder="Add Task"
							v-for="task in form.tasks"
							v-model="task.body"
							v-show="shown" v-focus>
					</div>
					<span class="flex mb-2 text-xs italic text-error" v-if="emptyTask">Fill above task</span>

					<button type="button" v-model="shown" class="inline-flex items-center text-xs" @click="addTask();">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" class="mr-2">
	                        <g fill="none" fill-rule="evenodd" opacity=".307">
	                            <path stroke="#757575" stroke-opacity=".012" stroke-width="0" d="M-3-3h24v24H-3z"></path>
	                            <path fill="#757575" d="M9 0a9 9 0 0 0-9 9c0 4.97 4.02 9 9 9A9 9 0 0 0 9 0zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7zm1-11H8v3H5v2h3v3h2v-3h3V8h-3V5z"></path>
	                        </g>
	                    </svg>
	                    <span class="text-default">Add New Task Field</span>
					</button>
				</div>
			</div>
			<footer class="flex justify-end">
				<button
					type="button"
					class="button is-outlined mr-4"
					style="color: #47cdff;"
					@click="$modal.hide('new-project')"
				>Cancel</button>
				<button class="button text-card">Create Project</button>
			</footer>
		</form>
	</modal>
</template>

<script>
	import BirdboardForm from './BirdboardForm';
	export default {
		data() {
			return {
				shown: false,
				emptyTask: false,
				form: new BirdboardForm({
					title: '',
					description: '',
					tasks: [
						// {body : ''},
					]
				}),
				// errors: {}
			}
		},
		methods: {
			addTask() {
				let len = this.form.tasks.length;
				if (len === 0 || this.form.tasks[len-1].body) {
					this.form.tasks.push({ body: '' });
					this.shown = true;
					this.emptyTask = false;
				} else {
					this.emptyTask = true;
				}
				
				// let task = document.getElementsByClassName('taskInput');
				// let taskLen = task.length;
				// task[taskLen].setAttribute('v-focus', true);
			},

			submit() {
				let len = this.form.tasks.length;
				if (! this.form.tasks[len-1].body) {
					this.form.tasks.splice(len-1, 1);
				}
				
				this.form.submit('/projects')
					.then(response => location = response.data.message);
					// .catch(error => alert('error'));
				// axios.post('/projects', this.form)
				// 	.then(response => {
				// 		location = response.data.message;
				// 	})
				// 	.catch(error => {
				// 		this.errors = error.response.data.errors;
				// 	});
			}
		},
		directives: {
			focus: {
				inserted (el) {
					el.focus();
				}
			}
		}
	}
</script>