/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('loi-search', require('./components/LoiSearch.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
Vue.component('select-2', {
    template: '<select v-bind:name="name" class="" v-bind:multiple="multiple"></select>',
    props: {
        name: '',
        options: {
            Object
        },
        value: null,
        multiple: {
            Boolean,
            default: false
        }
    },
    data() {
        return {
            select2data: []
        }
    },
    mounted() {
        this.formatOptions()
        let vm = this
        let select = $(this.$el)
        select
            .select2({
                placeholder: 'Select',
                theme: 'bootstrap',
                width: '100%',
                allowClear: true,
                data: this.select2data
            })
            .on('change', function () {
                vm.$emit('input', select.val())
            })
        select.val(this.value).trigger('change')
    },
    methods: {
        formatOptions() {
            this.select2data.push({ id: '', text: 'Select' })
            for (let key in this.options) {
                this.select2data.push({ id: key, text: this.options[key] })
            }
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
});
*/

const options = {
    apples: 'green',
    bananas: 'yellow',
    orange: 'orange'
}

const app = new Vue({
    el: '#app',
});
