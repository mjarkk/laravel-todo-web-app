<template>
	<div class="mainEl">
		<form class="addItem" @submit.prevent="addItem">
			<input type="text" placeholder="nieuw item" v-model="newItem">
			<button type="submit"><addIcon/></button>
		</form>
		<h3 class="subTitle" v-if="list.reduce((acc, el) => el.dune ? acc : acc + 1, 0) > 0">Todo's</h3>
		<div class="item" v-bind:key="item.id" v-for="(item, i) of list" v-if="!item.dune">
			<button class="check" v-on:click="changeItemState(i)"><uncheckedIcon/></button>
			<div class="text" v-on:click="editItem(i)">
				<span v-if="!item.edit">{{ item.title }}</span>
				<input 
					autofocus 
					v-bind:ref="'input-' + i" 
					v-on:keyup.enter="editItem(i, false)" 
					v-on:blur="editItem(i, false)"
					v-else v-model="item.title" 
					type="text">
			</div>
		</div>
		<h3 class="subTitle" v-if="list.reduce((acc, el) => el.dune ? acc + 1 : acc, 0) > 0">
			<span>Klaar</span>
			<button v-on:click="removeItem"><deleteAllIcon/></button>
		</h3>
		<div class="item itemDune" v-bind:key="item.id" v-for="(item, i) of list" v-if="item.dune">
			<button class="check" v-on:click="changeItemState(i)"><checkedIcon/></button>
			<div class="text" v-on:click="editItem(i)">
				<span v-if="!item.edit">{{ item.title }}</span>
				<input 
					autofocus 
					v-bind:ref="'input-' + i" 
					v-on:keyup.enter="editItem(i, false)" 
					v-on:blur="editItem(i, false)"
					v-else v-model="item.title" 
					type="text">
			</div>
			<button class="delete" v-on:click="removeItem(item.id)"><deleteIcon/></button>
		</div>
	</div>
</template>

<script>
	import Vue from 'vue'
	import axios from 'axios'
	import functions from '../functions.js'

	// icons
	import deleteIcon from './icons/delete.vue'
	import addIcon from './icons/add.vue'
	import deleteAllIcon from './icons/deleteall.vue'
	import uncheckedIcon from './icons/unchecked.vue'
	import checkedIcon from './icons/checked.vue'

	// create components
	Vue.component('deleteIcon', deleteIcon)
	Vue.component('deleteAllIcon', deleteAllIcon)
	Vue.component('checkedIcon', checkedIcon)
	Vue.component('uncheckedIcon', uncheckedIcon)
	Vue.component('addIcon', addIcon)
	
	export default {
		data: () => ({
			loading: true,
			list: [],
			newItem: ''
		}),
		methods: {
			addItem() {
				if (this.newItem != '') {
					let title = this.newItem
					functions.fetch('/api/add', {title: title}, jsonData => {
						if (jsonData.data && typeof jsonData.data.id == 'number') {
							let toAdd = jsonData.data
							toAdd['dune'] = false
							toAdd['edit'] = false
							this.list.unshift(toAdd)
						}
					})
					this.newItem = ''
				}
			},
			editItem(id, status) {
				let item = this.list[id]
				if (item) {
					let toSet = !item.edit
					if (typeof status == 'boolean') {
						toSet = status
					}
					item.edit = toSet
					if (!toSet && item && (!item.lastTitle || item.lastTitle != item.title)) {
						item.lastTitle = item.title
						if (item.title == '') {
							this.removeItem(item.id)
						} else {
							functions.fetch('/api/changetitle/' + item.id, {title: item.title})
						}
					} else if (toSet) {
						this.list[id]['lastTitle'] = this.list[id].title
						this.$nextTick(() => 
							this.$refs['input-' + id][0].focus()
						)
					}
				}
			},
			changeItemState(id) {
				let toSet = !this.list[id].dune
				this.list[id].dune = toSet
				functions.fetch('/api/changestate/' + this.list[id].id, {dune: toSet})
			},
			removeItem(id) {
				if (typeof id == 'number') {
					// find the index of item
					functions.fetch('/api/remove/' + id)
					let index = this.list.reduce((acc, el, i) => el.id == id ? i : acc, undefined)
					this.list.splice(index, 1)
				} else {
					// remove all items that are dune
					functions.fetch('/api/removeall/')
					this.list = this.list.map(el => el.dune ? undefined : el).filter(el => el)
				}
			}
		},
		mounted() {
			let vm = this
			axios.get('/api/list')
			.then(res => {
				vm.loading = false
				vm.list = res.data.map(el => {
					el['edit'] = false
					return el
				})
				vm.list = res.data
			})
			.catch(err => 
				console.log(err)
			)
		}
	}
</script>
