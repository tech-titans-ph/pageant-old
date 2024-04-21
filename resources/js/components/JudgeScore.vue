<template>
    <div class="relative h-full">
        <div class="flex h-full">
            <div class="flex flex-col w-1/2 h-full">
                <div class="flex flex-col justify-end flex-grow bg-center bg-no-repeat bg-contain border-r"
                    :style="`background-image: url(` + contestantPicture + `);`">
                    <div class="flex items-center justify-center p-2 space-x-2 bg-white opacity-75">
                        <div
                            class="flex items-center justify-center flex-none w-10 h-10 font-bold text-white bg-green-600 rounded-full">
                            #{{ contestantNumber }}</div>
                        <div>
                            <div class="font-bold">{{ contestantName }}</div>
                            <div class="font-thin">{{ contestantDescription }}</div>
                        </div>
                    </div>
                </div>
                <div class="flex-none p-2 border-t">
                    <div class="px-4 py-2 font-medium">&nbsp;</div>
                </div>
            </div>
            <div class="flex flex-col w-1/2 h-full">
                <div class="flex-grow overflow-y-auto">
                    <div class="border-b">
                        <slot></slot>
                    </div>
                </div>
                <div class="flex items-center justify-between flex-none p-2 border-t">
                    <div></div>
                    <div class="flex items-center px-4 py-2 space-x-1 font-bold text-gray-700">
                        <span>Score:</span>
                        <span>{{ totalScore }}</span>
                        <span>/</span>
                        <span>{{ totalPercentage }}</span>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 bottom-0 flex items-center justify-between p-2">
            <div>
                <a :href="previousUrl"
                    :class="[previousUrl == 'javascript:void(0);' ? ' bg-white hover:bg-white cursor-not-allowed border-none' : 'bg-green-600 hover:bg-green-500 shadow focus:outline-none focus:shadow-outline']"
                    class="flex items-center px-4 py-2 space-x-2 font-medium text-center text-white rounded-full">
                    <svg v-show="previousUrl != 'javascript:void(0);'"
                        class="feather feather-chevron-left sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    <span>Previous</span>
                </a>
            </div>
            <div>
                <a :href="resultUrl"
                    class="inline-block px-4 py-2 font-medium text-center text-white bg-green-600 rounded-full shadow focus:outline-none focus:shadow-outline hover:bg-green-500">Score
                    Results</a>
            </div>
            <div>
                <a :href="nextUrl"
                    :class="[nextUrl == 'javascript:void(0);' ? 'hover:bg-white cursor-not-allowed border-none' : 'shadow focus:outline-none focus:shadow-outline hover:bg-green-500 bg-green-600']"
                    class="flex items-center px-4 py-2 space-x-2 font-medium text-center text-white rounded-full">
                    <span>Next</span>
                    <svg v-show="nextUrl != 'javascript:void(0);'"
                        class="feather feather-chevron-right sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="276">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </a>
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
        },
        submitUrl: {
            required: true,
        },
        resultUrl: {
            required: true,
        },
        enabled: {
            type: Boolean,
            default: true
        },
        percentage: {
            default: 0
        },
        score: {
            default: 0
        },
    },
    data() {
        return {
            totalPercentage: 0,
            totalScore: 0
        };
    },
    mounted() {
        this.totalPercentage = this.percentage;
        this.totalScore = this.score;
    }
};
</script>
