<template>
    <div v-if="!loading" class="border-b divide-y">
        <template v-if="categories.length">
            <a v-for="category in categories" :key="category.id"
                :href="category.status === 'que' ? 'javascript:void(0);' : category.url"
                :class="[category.class, 'block px-6 py-4 space-y-2']">
                <div class="flex">
                    <h1 class="flex-grow text-lg font-bold">{{ category.name }}</h1>
                    <div class="flex-no-wrap flex-shrink text-right">
                        <span class="text-xl font-bold">{{ category.max_points_percentage }} {{ category.unit }}</span>
                    </div>
                </div>
                <div class="italic font-medium">{{ category.title }}</div>
            </a>
        </template>
        <div v-else class="px-6 py-4">There are no available categories for you in this contest.</div>
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
            categories: () => [],
            loading: true
        };
    },
    mounted() {
        this.listCategories();
        setInterval(() => {
            this.listCategories();
        }, 1000);
    },
    methods: {
        listCategories() {
            axios
                .get(this.api)
                .then(response => {
                    this.categories = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error.response);
                });
        }
    }
};
</script>
