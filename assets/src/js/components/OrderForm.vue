<template>
    <div class="vue-component-order-form">
        <b-modal ref="editForm" v-bind:title="order.id ? 'Edit Order' : 'Create Order'" @ok="saveOrder(order)">
            <div class="form-group">
                <label>Name</label>
                <input type="text" v-model="order.name" class="form-control">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select v-model="order.status" class="form-control">
                    <option value="canceled">Canceled</option>
                    <option value="closed">Closed</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                    <option value="open">Open</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                </select>
            </div>
            <div class="form-group">
                <label>subtotal</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" v-model="order.subtotal" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Shipping</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" v-model="order.shipping" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Total</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" v-model="order.total" class="form-control">
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            order: this.defaultOrder()
        }
    },

    methods: {
        /**
         * Create a default order when creating
         * a new order.
         */
        defaultOrder() {
            return {
                name: '',
                status: 'open',
                subtotal: '0.00',
                shipping: '0.00',
                total: '0.00'
            };
        },

        /**
         * Send a create/update request to our API
         */
        saveOrder(order) {
            //If there's an order id, we know it's an existing order and not a new order
            var method = order.id ? 'update/' + order.id : 'store';

            axios.post('/api/orders/' + method, qs.stringify(order))
            .then(response => {
                var message = order.id ? 'Order #' + order.id + ' has been updated.' : 'Order has been successfully created.';
                this.$root.$emit('alert-message', message);
            })
            .catch(error => {
                var response = error.response;
                this.$root.$emit('alert-error', response.data.message);
            });;
        }
    },

    created() {
        //Listen for an 'open-order-form' event, and show the modal
        this.$root.$on('open-order-form', order => {
            //$refs reference elements in our template
            this.$refs.editForm.show();
            //If we aren't passed an order, create a default one.
            this.order = order ? order : this.defaultOrder();
        });
    }
}
</script>
