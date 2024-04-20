<template>
    <div class="relative inline-block text-left">
        <div id="logoDropdown" class="px-6 py-1" role="button" @click="toggleDropdown">
            <div class="font-bold text-center text-2xs">{{ appName }}</div>
            <img :src="logoUrl" class="h-16 mx-auto">
        </div>
        <div v-show="open"
            class="absolute right-0 z-10 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1"
            v-click-outside="hideDropdown">
            <div class="py-1" role="none">
                <form method="POST" :action="logoutUrl" role="none">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-gray-700" role="menuitem"
                        tabindex="-1" id="menu-item-3">Logout</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        appName: { required: true },
        logoUrl: { required: true },
        logoutUrl: { required: true },
        csrfToken: { required: true },
    },
    data() {
        return {
            open: false,
        };
    },
    methods: {
        toggleDropdown() {
            this.open = !this.open;
        },
        hideDropdown(event) {
            if (event.target.id == "logoDropdown") {
                return;
            }

            var logoDropdown = event.target.closest('#logoDropdown');

            if (logoDropdown) {
                return;
            }

            this.open = false;
        }
    }
}
</script>
