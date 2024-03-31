<template>
    <div ref="wrapper" class="p-4"></div>
</template>

<script>
export default {
    props: {
        api: {
            required: true
        }
    },
    mounted() {
        setInterval(() => {
            axios.get(this.api)
                .then(response => {
                    this.$refs.wrapper.innerHTML = response.data;
                }).catch(error => {
                    console.log(error.response);
                })/* .finally(() => {
                    clearInterval(liveScore);
                }) */;
        }, 1000);
    }
};
</script>
