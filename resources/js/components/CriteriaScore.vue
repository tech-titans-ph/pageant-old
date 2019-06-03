<template>
  <div class="border-t py-6 px-6">
    <div class="flex">
      <h1 class="text-lg font-bold flex-1 text-gray-700 mb-4">{{ name }}</h1>
      <div class="flex-1 text-right text-gray-700 text-sm">
        <span class="font-bold text-xl">{{ scoreValue }}</span>/{{ percentage }}
      </div>
    </div>
    <input type="range" min="0" :max="percentage" v-model="scoreValue" @change="setScore" class="w-full appearance-none bg-gray-400 h-2">
  </div>
</template>

<script>
export default {
  props: {
    score_id: {
      required: true
		},
		name: {
			required: true,
		},
    percentage: {
      required: true
    },
    score: {
      required: true
    }
  },
  data() {
    return {
			scoreValue: this.score,
		};
  },
  methods: {
		setScore(){
			axios.patch('/judge-score/' + this.score_id, {
				score: this.scoreValue,
			}).then((response) => {
				console.log(response.data.totalScore);
				this.$parent.totalScore = response.data.totalScore;
			}).catch((error) => {
				console.log(error);
			});
			// this.$emit('score', this.score_id);
		}
	}
};
</script>