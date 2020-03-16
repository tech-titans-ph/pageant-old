<template>
	<div v-if="!loading">
		<template v-if="categoryJudges.length">
			<a
				v-for="categoryJudge in categoryJudges"
				:key="categoryJudge.id"
				:href="categoryJudge.category.status === 'que' ? 'javascript:void(0);' : categoryJudge.url"
				:class="[categoryJudge.class, 'block border-t p-6']"
			>
				<div class="flex">
					<h1 class="text-lg font-bold flex-1 mb-4">{{ categoryJudge.category.name }}</h1>
					<div class="flex-1 text-right">
						<span class="font-bold text-xl">{{ categoryJudge.category.percentage }}%</span>
					</div>
				</div>
				<div class="italic font-medium">{{ categoryJudge.title }}</div>
			</a>
		</template>
		<div v-else class="border-t p-6">There are no available categories for you in this contest.</div>
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
			categoryJudges: () => [],
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
					this.categoryJudges = response.data;
					this.loading = false;
				})
				.catch(error => {
					console.log(error.response);
				});
		}
	}
};
</script>