<template>
	<div class="border-t py-6 px-6">
		<div class="flex">
			<h1 class="text-lg font-bold flex-1 text-gray-700 mb-4">{{ name }}</h1>
			<div class="flex-1 text-right text-gray-700 text-sm">
				<span class="font-bold text-xl">{{ scoreValue }}</span>
				/{{ percentage }}
			</div>
		</div>
		<div class="flex items-center">
			<button
				type="button"
				v-if="enabled"
				@click="decreaseScore"
				class="flex-none rounded-full bg-green-600 active:bg-green-400 text-white p-2 focus:outline-none focus:shadow-outline"
			>
				<slot name="decrease-icon"></slot>
			</button>
			<input
				type="range"
				min="0"
				:max="percentage"
				v-model="scoreValue"
				@change="setScore"
				class="flex-grow w-full appearance-none bg-gray-400 h-2 mx-3"
				v-if="enabled"
			/>

			<button
				type="button"
				v-if="enabled"
				@click="increaseScore"
				class="flex-none rounded-full bg-green-600 active:bg-green-400 text-white p-2 focus:outline-none focus:shadow-outline"
			>
				<slot name="increase-icon"></slot>
			</button>
		</div>
		<div v-if="error" class="mt-2 text-sm italic text-red-500 text-center">{{ error }}</div>
	</div>
</template>

<script>
export default {
	props: {
		api: {
			required: true
		},
		id: {
			required: true
		},
		name: {
			required: true
		},
		percentage: {
			required: true
		},
		score: {
			required: true
		},
		enabled: {
			type: Boolean,
			default: true
		}
	},
	data() {
		return {
			scoreValue: this.score,
			error: ""
		};
	},
	methods: {
		setScore: _.debounce(function() {
			this.error = "";

			axios
				.patch(this.api, {
					criteria_id: this.id,
					score: this.scoreValue
				})
				.then(response => {
					this.$parent.totalScore = response.data.totalScore;
				})
				.catch(error => {
					if (error.response.status == 422) {
						var errors = error.response.data.errors;

						var key = Object.keys(errors)[0];

						this.error = errors[key][0];
					} else {
						console.log(error.response);
					}
				});
		}, 250),
		decreaseScore() {
			if (Number(this.scoreValue) <= 0) {
				return;
			}

			this.scoreValue = Number(this.scoreValue) - 1;

			this.setScore();
		},
		increaseScore() {
			if (Number(this.scoreValue >= Number(this.percentage))) {
				return;
			}

			this.scoreValue = Number(this.scoreValue) + 1;

			this.setScore();
		}
	}
};
</script>