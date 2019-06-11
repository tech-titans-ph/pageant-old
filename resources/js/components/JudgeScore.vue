<template>
  <div>
    <div class="mx-auto pt-12 pb-12">
      <div class="border-b p-4 text-center leading-normal">
        <h2 class="text-lg font-bold">{{ contestName }}</h2>
        <p class="text-md font-semibold">{{ categoryName }}</p>
        <p class="italic font-thin text-sm">Judge: {{ judgeName }}</p>
      </div>
      <div class="md:rounded md:shadow-md mx-auto">
        <div class="md:flex justify-center md:justify-between border-t">
          <div class="w-full md:w-1/2">
            <img :src="contestantPicture" class="w-full object-contain" alt="Contestant">
          </div>
          <div class="w-auto md:w-1/2 flex md:static p-4 justify-between items-center">
            <a
              :href="previousUrl"
              class="border rounded-full flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200"
            >
              <svg
                class="feather feather-chevron-left sc-dnqmqq jxshSx"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </a>
            <div class="flex items-center justify-center md:px-8">
              <div
                class="flex items-center justify-center bg-green-600 rounded-full text-white mr-4 font-bold w-12 h-12"
              >#{{ contestantNumber }}</div>
              <div>
                <div class="font-bold">{{ contestantName }}</div>
                <div class="font-thin">{{ contestantDescription }}</div>
              </div>
            </div>
            <a
              :href="nextUrl"
              class="border rounded-full flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200"
            >
              <svg
                class="feather feather-chevron-right sc-dnqmqq jxshSx"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
                data-reactid="276"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </a>
          </div>
        </div>
        <slot></slot>
      </div>
    </div>
    <div class="fixed bottom-0 h-12 border-t bg-white w-full flex justify-center items-center">
      <div class="px-2">
        <div class="font-thin text-lg">
          Score:
          <span class="font-semibold">{{ totalScore }}/{{ totalPercentage }}</span>
        </div>
      </div>
      <div class="px-2">
        <button @click="nextContestant"
          type="button"
          class="flex items-center items-around shadow bg-green-600 rounded-full py-1 px-1 text-white"
        >
          <span class="pl-4">Submit</span>
          <svg
            class="ml-2"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 16 16 12 12 8"></polyline>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contestName: {
      required: true
    },
    categoryName: {
      required: true
    },
    judgeName: {
      required: true
    },
    contestantNumber: {
      required: true
    },
    contestantName: {
      required: true
    },
    contestantDescription: {
      required: true
    },
    contestantPicture: {
      required: true
    },
    previousUrl: {
      required: true
    },
    nextUrl: {
      required: true
    }
  },
  mounted() {
    this.$children.forEach(criteria => {
      this.totalPercentage += (criteria.percentage - 0);
      this.totalScore += (criteria.scoreValue - 0);
    });
	},
	data(){
		return {
			totalPercentage: 0,
			totalScore: 0,
		}
	},
  methods: {
		nextContestant(){
			window.location.href = this.nextUrl;
		}
	}
};
</script>