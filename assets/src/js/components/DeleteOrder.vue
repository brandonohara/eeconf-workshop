<template>
    <div class="vue-component-delete-order">
        <b-modal ref="deleteForm" title="Delete Order" @ok="destroyOrder(order)">
            <p>Are you sure you want to delete Order #{{ order.id }}?</p>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            order: {}
        }
    },

    methods: {
        /**
         * SEnd a delete request to the API, emit an event that the
         * Order has been deleted, and show a success message.
         */
        destroyOrder(order) {
            axios.post('/api/orders/destroy/' + order.id)
            .then(response => {
                var id = response.data.data;
                //emit an event to tell our orders component to remove this order if it's visible.
                this.$root.$emit('order-deleted', id);
                //show a success message
                this.$root.$emit('alert-message', 'Order #' + id + ' successfully deleted.');
            });
        }
    },

    created() {
        // listen for the event 'open-delete-order' and show the form
        this.$root.$on('open-delete-order', order => {
            //$refs are ways to target elements in the template
            this.$refs.deleteForm.show();
            //set the passed order
            this.order = order;
        });
    }
}
</script>
