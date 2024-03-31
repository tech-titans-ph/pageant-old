<template>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="p-1 border-r" colspan="2">Contestants</th>
                    <th class="p-1 border-r">Judges</th>
                    <th class="p-1 border-r" v-for="(criteria) in criterias" :key="criteria.id + '-criteria'">
                        {{ criteria.name }}
                        <br />
                        {{ criteria.percentage }}%
                    </th>
                    <th class="p-1 border-r">Total</th>
                    <th class="p-1">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(categoryContestant, top) in categoryContestants">
                    <tr class="border-t" v-for="(categoryJudge, i) in categoryContestant.categoryJudges"
                        :key="categoryContestant.id + '-category-contestant-' + categoryJudge.id + '-category-judge'">
                        <template v-if="i <= 0">
                            <td class="w-40 p-1 text-center align-top border-r" style="min-width: 10rem;"
                                :rowspan="categoryContestant.categoryJudges.length">
                                <img :src="categoryContestant.contestant.picture_url"
                                    class="object-cover object-center w-32 h-32 mx-auto border rounded-full" />
                                Top {{ (top - 0) + 1 }}
                            </td>
                            <td class="w-auto p-1 align-top border-r"
                                :rowspan="categoryContestant.categoryJudges.length">
                                <span class="font-medium">#{{ categoryContestant.contestant.number }} - {{
                        categoryContestant.contestant.name }}</span>
                                <br />
                                <span class="italic">{{ categoryContestant.contestant.description }}</span>
                            </td>
                        </template>
                        <td class="p-1 border-r">{{ categoryJudge.judge.user.name }}</td>
                        <td class="p-1 text-center border-r" v-for="criteriaScore in categoryJudge.criteriaScores"
                            :key="criteriaScore.id + '-criteria-score'">{{ criteriaScore.score }}</td>
                        <td class="p-1 font-medium text-center border-r">{{ categoryJudge.total }}</td>
                        <td class="p-1 font-medium text-center">{{ categoryJudge.percentage | round }}</td>
                    </tr>
                    <tr class="border-t" :key="categoryContestant.id + '-average'">
                        <td class="p-1 font-bold text-right border-r" :colspan="(criterias.length - 0) + 4">Average:
                        </td>
                        <td class="p-1 font-bold text-center">{{ categoryContestant.averagePercentage | round }}</td>
                    </tr>
                    <tr class="border-t" :key="categoryContestant.id + '-divider'">
                        <td class="p-1" :colspan="(criterias.length - 0) + 5">&nbsp;</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        api: {
            required: true
        }
    },
    data() {
        return {
            criterias: () => [],
            categoryContestants: () => []
        };
    },
    filters: {
        round: function (value) {
            return parseFloat(value).toFixed(4);
        }
    },
    mounted() {
        var liveScore = setInterval(() => {
            axios
                .get(this.api)
                .then(response => {
                    this.criterias = response.data.criterias;
                    this.categoryContestants = response.data.categoryContestants;
                })
                .catch(error => {
                    console.log(error.response);
                })
                .finally(() => {
                    // clearInterval(liveScore);
                });
        }, 1000);
    }
};
</script>
