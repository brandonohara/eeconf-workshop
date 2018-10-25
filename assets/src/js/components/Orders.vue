<template>

    <div class="vue-component-orders table-responsive">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Orders & Things</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a @click="orderForm()" class="nav-link">
                            <span class="fas fa-plus"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="padding-bottom">
            <div class="form-row">
                <div class="col col-md-3 col-lg-2">
                    <select v-model="parameters.status" v-on:change="filter" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="canceled">Canceled</option>
                        <option value="closed">Closed</option>
                        <option value="delivered">Delivered</option>
                        <option value="failed">Failed</option>
                        <option value="open">Open</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                    </select>
                </div>
                <div class="col col-md-9 col-lg-10">
                    <input type="text" v-model="parameters.search" v-on:keyup="filter" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover" v-show="orders.length">
            <thead>
                <tr>
                    <sortable-table-header name="Order #" column="id" :is-selected="true"></sortable-table-header>
                    <sortable-table-header name="Status" column="status" :is-selected="false"></sortable-table-header>
                    <sortable-table-header name="Name" column="name" :is-selected="false"></sortable-table-header>
                    <sortable-table-header name="Total" column="total" :is-selected="false"></sortable-table-header>
                    <sortable-table-header name="Date" column="created_at" :is-selected="false"></sortable-table-header>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in this.orders">
                    <td>Order #{{ order.id }}</td>
                    <td>{{ order.status }}</td>
                    <td>{{ order.name }}</td>
                    <td>${{ order.total.toFixed(2) }}</td>
                    <td>{{ order.created_at }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="orderForm(order)"><span class="fas fa-edit"></span></button>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="deleteOrder(order)"><span class="fas fa-trash"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="text-center padding" v-if="loading"><span class="fas fa-sync fa-spin"></span> Loading</p>
        <p class="text-center padding" v-if="!loading && !orders.length">There are no orders with these filters.</p>

        <p class="text-center" v-if="!loading && orders.length && !reachedEnd">
            <button type="button" @click="loadMore()" class="btn btn-primary">Load More</button>
        </p>

        <div>
            <order-form></order-form>
            <delete-order></delete-order>
            <alert></alert>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            reachedEnd: false,
            parameters: {
                limit: 25,
                offset: 0,
                orderby: 'id',
                sort: 'asc',
                status: '',
                search: ''
            },
            orders: []
        }
    },

    methods: {
        /**
         * Method to retrieve orders from the API
         */
        getOrders() {
            this.loading = true;
            axios.get('/api/orders/index', {
                params: this.parameters
            }).then(response => {
                var data = response.data.data;

                //if the number of returned orders is less than our limit, we've reached the end
                if (data.length < this.parameters.limit) {
                    this.reachedEnd = true;
                }

                //Combine orders with the new orders
                this.orders = this.orders.concat(data);
                this.loading = false;
            });
        },

        /**
         * Increase our offset, and get the next set of orders
         */
        loadMore() {
            this.parameters.offset += this.parameters.limit;
            this.getOrders();
        },

        /**
         * Reset the orders after a new filter has been changed, get new orders
         */
        filter() {
            this.reset();
            this.getOrders();
        },

        /**
         * Empty the orders, set the offset back to zero, and reset the reachEnd flag
         */
        reset() {
            this.orders = [];
            this.parameters.offset = 0;
            this.reachedEnd = true;
        },

        /**
         * Emit an event to open the order form component
         */
        orderForm(order) {
            this.$root.$emit('open-order-form', order);
        },
        /**
         * Emit an event to open the delete order component
         */
        deleteOrder(order) {
            this.$root.$emit('open-delete-order', order);
        }
    },

    /**
     * Vue specific method, called when the component has been created
     */
    created() {
        //Get orders right away
        this.getOrders();  

        //Listen for an event called by the Sortable Table Header component, 
        //and change the orderby and sort parameters
        this.$root.$on('orderby-changed', data => {
            this.parameters.orderby = data.orderby;
            this.parameters.sort = data.sort;
            this.reset();
            this.getOrders();
        });

        //when an order has been deleted, remove it from our orders array
        this.$root.$on('order-deleted', order_id => {
            //use lodash to remove any orders that match the deleted order id
            _.remove(this.orders, order => order.id == order_id);
            //this is some Vue trickery, we need to "edit" the orders for it to refresh
            this.orders = [...this.orders];
        });
    }
}
</script>
