<template>
	<div>
		<input
			type="text"
			v-model="dataDisplayValue"
			:name="this.displayName"
			:class="inputClass"
			:placeholder="placeholder"
			@focus="onFocus"
			@click="onClick"
			@input="getItems(true)"
			@keydown.down.prevent="onArrowDown"
			@keydown.up.prevent="onArrowUp"
			@keydown.enter.prevent="onEnter"
			@keydown.esc="onEscape"
		/>
		<input type="hidden" v-if="dataHiddenValue" :name="hiddenName" v-model="dataHiddenValue" />
		<div class="relative w-full">
			<div v-if="isOpen && items.length" :class="menuClass" class="absolute">
				<ul
					ref="menuScroll"
					class="min-h-0 overflow-y-auto overflow-x-hidden w-full"
					style="max-height: 160px;"
				>
					<li :class="itemClass" v-if="isLoading">Loading results...</li>
					<li
						v-else
						v-for="(item, index) in items"
						:key="index"
						@click="setItem(item, index)"
						:class="{[itemClass]: true, 'border-t': index, [activeItemClass]: index == activeItemIndex}"
					>{{ item[displayProperty] }}</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		api: {
			required: true
		},
		hiddenName: {
			required: true
		},
		displayName: {
			required: true
		},
		hiddenProperty: {
			required: true
		},
		displayProperty: {
			required: true
		},
		hiddenValue: {
			default: ""
		},
		displayValue: {
			default: ""
		},
		placeholder: {
			default: ""
		},
		inputClass: {
			default: "form-select block w-full"
		},
		menuClass: {
			default: "w-full bg-white z-10 rounded border overflow-hidden mt-1 mb-4"
		},
		itemClass: {
			default: "cursor-pointer hover:bg-green-600 hover:text-white px-4 py-2"
		},
		activeItemClass: {
			default: "bg-green-600 text-white"
		}
	},
	data() {
		return {
			isOpen: false,
			items: () => [],
			dataHiddenValue: "",
			dataDisplayValue: "",
			activeItemIndex: -1,
			isLoading: false,
			isSettingItem: false,
			isFocusing: false
		};
	},
	mounted() {
		this.dataHiddenValue = this.hiddenValue;
		this.dataDisplayValue = this.displayValue;

		document.addEventListener("click", this.handleClickOutside);
		document.addEventListener("focusin", this.handleFocusOutside);
	},
	destroyed() {
		document.removeEventListener("click", this.handleClickOutside);
		document.removeEventListener("focusin", this.handleFocusOutside);
	},
	methods: {
		onFocus() {
			this.isFocusing = true;

			this.getItems(false);
		},
		onClick() {
			if (this.isFocusing) {
				this.isFocusing = false;
				return;
			}

			this.getItems(false);
		},
		onEscape() {
			if (!this.dataHiddenValue) {
				this.activeItemIndex = -1;
			}

			this.isOpen = false;
		},
		getItems: _.debounce(function(search) {
			if (this.isSettingItem) {
				this.isSettingItem = false;
				return;
			}

			this.isFocusing = false;

			this.isOpen = true;

			var api = this.api;

			if (search) {
				this.dataHiddenValue = "";
				this.activeItemIndex = -1;
				api = api + "?search-keyword=" + this.dataDisplayValue;
			}

			this.isLoading = true;

			axios
				.get(api)
				.then(response => {
					this.items = response.data;
				})
				.catch(error => {
					console.log(error);
				})
				.finally(() => {
					this.isLoading = false;

					setTimeout(() => {
						this.activeItemIndex = _.findIndex(this.items, item => {
							return item[this.hiddenProperty] == this.dataHiddenValue;
						});

						if (this.activeItemIndex < 0 && this.items.length) {
							this.activeItemIndex = 0;
						}

						if (this.activeItemIndex > -1) {
							this.$refs.menuScroll.children[
								this.activeItemIndex
							].scrollIntoView();
						}
					}, 250);
				});
		}, 500),
		setItem(item, index) {
			this.activeItemIndex = index;
			this.dataHiddenValue = item[this.hiddenProperty];
			this.dataDisplayValue = item[this.displayProperty];
			this.isOpen = false;
			this.isSettingItem = true;
		},
		onArrowDown(evt) {
			if (this.activeItemIndex < this.items.length - 1) {
				this.activeItemIndex = this.activeItemIndex + 1;
				this.$refs.menuScroll.children[this.activeItemIndex].scrollIntoView();
			}
		},
		onArrowUp() {
			if (this.activeItemIndex > 0) {
				this.activeItemIndex = this.activeItemIndex - 1;
				this.$refs.menuScroll.children[this.activeItemIndex].scrollIntoView();
			}
		},
		onEnter() {
			if (this.activeItemIndex < 0) {
				return;
			}

			this.setItem(this.items[this.activeItemIndex], this.activeItemIndex);
			this.isSettingItem = false;
		},
		handleClickOutside(event) {
			if (!this.$el.contains(event.target)) {
				this.isOpen = false;
			}
		},
		handleFocusOutside(event) {
			if (!this.$el.contains(event.target)) {
				this.isOpen = false;
			}
		}
	}
};
</script>
