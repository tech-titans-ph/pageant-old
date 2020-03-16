<template>
	<div class="hidden"></div>
</template>

<script>
export default {
	props: {
		api: {
			required: true
		}
	},
	mounted() {
		var checkStatus = setInterval(() => {
			axios
				.get(this.api)
				.then(response => {
					if (!_.isEmpty(response.data)) {
						clearInterval(checkStatus);
						swal
							.fire({
								customClass: {
									confirmButton:
										"inline-block text-center font-medium px-4 py-2 rounded-full shadow text-white focus:outline-none focus:shadow-outline bg-green-600 hover:bg-green-500"
								},
								buttonsStyling: false,
								showCloseButton: true,
								toast: true,
								position: "bottom-end",
								icon: "info",
								titleText:
									response.data.category.name +
									" is now available for scoring.",
								confirmButtonText: "Start Scoring",
								onOpen: toast => {
									swal.stopTimer;
								}
							})
							.then(result => {
								if (result.value) {
									window.location.href =
										"/judge/categories/" +
										response.data.category_id +
										"/contestants";
								}
							});
					}
				})
				.catch(error => {
					console.log(error.response);
				});
		}, 1000);
	}
};
</script>