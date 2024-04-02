<template>
    <div>
        <div class="pt-12 pb-12 mx-auto">
            <div class="p-4 leading-normal text-center border-b">
                <h2 class="text-lg font-bold">{{ contestName }}</h2>
                <p class="font-semibold text-md">{{ categoryName }}</p>
                <p class="text-sm italic font-thin">Judge: {{ judgeName }}</p>
                <p v-if="enabled" class="mt-2 text-sm">(Please scroll down to start putting your scores)</p>
            </div>
            <div class="mx-auto md:rounded md:shadow-md">
                <div class="justify-center border-t md:flex md:justify-between">
                    <div class="w-full md:w-1/2">
                        <img :src="contestantPicture" class="object-contain w-full" alt="Contestant" />
                    </div>
                    <div class="flex items-center justify-between w-auto p-4 md:w-1/2 md:static">
                        <a :href="previousUrl"
                            class="flex items-center justify-center flex-none block w-12 h-12 mr-1 no-underline border rounded-full hover:bg-gray-200">
                            <svg class="feather feather-chevron-left sc-dnqmqq jxshSx"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </a>
                        <div class="flex items-center justify-center md:px-8">
                            <div
                                class="flex items-center justify-center flex-none w-12 h-12 mr-4 font-bold text-white bg-green-600 rounded-full">
                                #{{ contestantNumber }}</div>
                            <div>
                                <div class="font-bold">{{ contestantName }}</div>
                                <div class="font-thin">{{ contestantDescription }}</div>
                            </div>
                        </div>
                        <a :href="nextUrl"
                            class="flex items-center justify-center flex-none block w-12 h-12 ml-1 no-underline border rounded-full hover:bg-gray-200">
                            <svg class="feather feather-chevron-right sc-dnqmqq jxshSx"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true" data-reactid="276">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                    </div>
                </div>
                <slot></slot>
            </div>
        </div>
        <div class="fixed bottom-0 flex items-center justify-center w-full h-12 bg-white border-t">
            <div class="px-2">
                <div class="text-lg font-thin">
                    Score:
                    <span class="font-semibold">{{ totalScore }}/{{ totalPercentage }}</span>
                </div>
            </div>
            <div class="px-2" v-if="enabled">
                <a :href="nextUrl"
                    class="flex items-center px-1 py-1 text-white bg-green-600 rounded-full shadow items-around">
                    <span class="pl-4">Submit</span>
                    <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 16 16 12 12 8" />
                        <line x1="8" y1="12" x2="16" y2="12" />
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
