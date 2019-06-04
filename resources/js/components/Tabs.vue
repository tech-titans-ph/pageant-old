<template>
  <div>
    <ul class="tab">
      <li v-for="tab in tabs" :key="tab.title" :class="{'active': tab.isActive}">
        <a :href="tab.href" @click="selectTab(tab)">{{ tab.title }}</a>
      </li>
    </ul>
    <div class="tab-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tabs: []
    };
  },
  created() {
		this.tabs = this.$children;
  },
  methods: {
    selectTab(selectedTab) {
      this.tabs.forEach(tab => {
        tab.isActive = tab.title == selectedTab.title ? true : false;
      });
    }
  }
};
</script>