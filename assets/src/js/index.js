require('./bootstrap');

//Include Vue comonents
Vue.component('orders', require('./components/Orders.vue')); // Orders container component
Vue.component('order-form', require('./components/OrderForm.vue')); // Create and Update form component
Vue.component('delete-order', require('./components/DeleteOrder.vue')); // Delete order modal component
Vue.component('sortable-table-header', require('./components/SortableTableHeader.vue')); // Sortable Table Header component, click a column and sort
Vue.component('alert', require('./components/Alert.vue')); // Flash message component

//Vue container
const app = new Vue({
    el: '#app',
    components: {},
});
