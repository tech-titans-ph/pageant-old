<template>
    <div>
        <ul :class="classes.tab">
            <li v-for="tab in tabs" :key="tab.title"
                :class="[classes.tabItem, tab.isActive ? classes.activeTabItem : '']">
                <a :href="'javascript:void(0);'" @click="selectTab(tab)"
                    :class="[classes.tabItemLink, tab.isActive ? classes.activeTabItemLink : '']">{{ tab.title }}</a>
            </li>
        </ul>
        <div :class="classes.tabContent">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tabs: [],
            classes: {
                tab:
                    "flex p-1 lg:p-0 overflow-x-auto lg:overflow-x-hidden overflow-y-hidden",
                tabItem: "",
                tabItemLink:
                    "inline-block bg-white text-green-500 hover:text-green-800 font-semibold px-4 py-2",
                activeTabItem: "-mb-px",
                activeTabItemLink: "border-l border-t border-r rounded-t",
                tabContent: "border rounded-b"
            }
        };
    },
    created() {
        this.tabs = this.$children;
    },
    mounted() {
        var activeTab = new URL(location.href).searchParams.get("activeTab");

        if (!activeTab) {
            this.tabs[0].isActive = true;
            window.history.pushState(null, null, "?activeTab=" + this.tabs[0].title);
        } else {
            this.tabs.forEach(tab => {
                tab.isActive = tab.title === activeTab ? true : false;
            });
        }
    },
    methods: {
        selectTab(selectedTab) {
            window.history.pushState(null, null, "?activeTab=" + selectedTab.title);

            this.tabs.forEach(tab => {
                tab.isActive = tab.title == selectedTab.title ? true : false;
            });
        }
    }
};
</script>
