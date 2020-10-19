class BirdboardForm {

	constructor(data) {
		// this.originalData = data;

		// this.originalData = {};
		// Object.assign(this.originalData, data); // shallow merge

		this.originalData = JSON.parse(JSON.stringify(data)); // deep merge
		// stringify all the data into an object that will get rid of reactivity. Then we parse it back into an object and assign it.
		
		// this.data = data;
		// this.form.data.title
		Object.assign(this, data);
		// this.form.title

		this.errors = {};
		this.submitted = false;
	}

	data() {

		return Object.keys(this.originalData).reduce((data, attribute) => {
			data[attribute] = this[attribute];
			return data;
		}, {});

		// let data = {};
		// for (let attribute in this.originalData) {
		// 	data[attribute] = this[attribute];
		// }
		// return data;
	}

	post(endpoint) {
		this.submit(endpoint);
	}
	patch(endpoint) {
		this.submit(endpoint, 'patch');
	}
	delete(endpoint) {
		this.submit(endpoint, 'delete');
	}

	submit(endpoint, requestType = 'post') {
		return axios[requestType](endpoint, this.data())
			.catch(this.onFail.bind(this))
			.then(this.onSuccess.bind(this));
	}

	onSuccess(response) {
		this.submitted = true;
		this.errors = {};
		return response;
	}

	onFail(error) {
		this.errors = error.response.data.errors;
		this.submitted = false;
		throw error;
	}

	reset() {
		Object.assign(this, this.originalData);
	}
}

export default BirdboardForm;