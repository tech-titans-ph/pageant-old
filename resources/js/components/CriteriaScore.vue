<template>
    <div class="px-6 py-6 border-t">
        <div class="flex space-x-4">
            <h1 class="flex-1 flex-shrink-0 mb-4 text-lg font-bold text-gray-700 whitespace-no-wrap">{{ name }}</h1>
            <div class="flex flex-col items-center">
                <input type="text" class="block w-20 mb-1 text-lg font-bold text-center form-input" v-model="scoreValue"
                    @change="setScore" />
                <label>Input manual score</label>
            </div>
            <div class="flex-1 flex-shrink-0 text-sm text-right text-gray-700">
                <span class="text-xl font-bold">{{ scoreValue }}</span>
                /{{ percentage }}
            </div>
        </div>
        <div class="flex items-center">
            <button type="button" v-if="enabled" @click="decreaseScore"
                class="flex-none p-2 text-white bg-green-600 rounded-full active:bg-green-400 focus:outline-none focus:shadow-outline">
                <slot name="decrease-icon"></slot>
            </button>
            <input type="range" min="0" :max="percentage" :step="step" v-model="scoreValue" @change="setScore"
                class="flex-grow w-full h-2 mx-3 bg-gray-400 appearance-none" v-if="enabled" />

            <button type="button" v-if="enabled" @click="increaseScore"
                class="flex-none p-2 text-white bg-green-600 rounded-full active:bg-green-400 focus:outline-none focus:shadow-outline">
                <slot name="increase-icon"></slot>
            </button>
        </div>
        <div v-if="error" class="mt-2 text-sm italic text-center text-red-500">{{ error }}</div>
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
            required: true,
        },
        step: {
            required: true,
        },
        score: {
            required: true,
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
        setScore: _.debounce(function () {
            this.error = "";

            axios.patch(this.api, {
                group_id: this.id,
                points: this.scoreValue
            }).then(response => {
                this.$parent.totalScore = response.data.totalScore;
            }).catch(error => {
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

            var wholeNumber = Math.trunc((Number(this.scoreValue) / Number(this.step)).toFixed(2));

            var remainder = Math.floor(Number(this.scoreValue) % Number(this.step));

            var nearest = (wholeNumber * Number(this.step)) + (remainder ? Number(this.step) : 0);

            var decimal = (Number(this.step) + '').split('.')[1]?.length;

            this.scoreValue = (nearest - Number(this.step)).toFixed(Number(this.step) == 1 ? 0 : decimal);

            this.setScore();

            this.setScore();
        },
        increaseScore() {
            if (Number(this.scoreValue) >= Number(this.percentage)) {
                return;
            }

            var wholeNumber = Math.trunc((Number(this.scoreValue) / Number(this.step)).toFixed(2));

            var nearest = wholeNumber * Number(this.step);

            var decimal = (Number(this.step) + '').split('.')[1]?.length;

            this.scoreValue = (nearest + Number(this.step)).toFixed(Number(this.step) == 1 ? 0 : decimal);

            this.setScore();
        }
    }
};
</script>
