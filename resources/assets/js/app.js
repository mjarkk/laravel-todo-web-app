
import Vue from 'vue'
import TodoList from './components/todoList.vue'

Vue.component('todolist', TodoList )

const app = new Vue({
    el: '.root'
})
