<template>
  <div class="tab-pane" v-show="isActive">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      required: true
    },
    active: {
      default: false
    }
  },
  data() {
    return {
      isActive: this.active
    };
  },
  computed: {
    href() {
      return '#' + this.title.toLowerCase().replace(/ /g, "-");
    }
  }
};
</script>