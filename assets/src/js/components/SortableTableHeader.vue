<template>
    <th class="cursor" @click="select()">
        {{ name }}
        <span
            class="fas"
            v-if="this.selected"
            v-bind:class="{ 'fa-angle-down': sort == 'asc', 'fa-angle-up': sort == 'desc' }"></span>
    </th>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true
        },
        column: {
            type: String,
            required: true
        },
        isSelected: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            selected: this.isSelected,
            sort: 'asc'
        }
    },

    methods: {
        /**
         * Toggle the sort if this header is already selected.
         */
        select() {
            this.sort = this.selected && this.sort === 'asc' ? 'desc' : 'asc';
            this.selected = true;

            //emit an event to tell the orders component the orderby and sort parameters have been changed
            this.$root.$emit('orderby-changed', {
                orderby: this.column,
                sort: this.sort
            })
        }
    },

    created() {
        //listen for an event that the orderby parameter has been changed, and then deselect if it doesn't match our column.
        this.$root.$on('orderby-changed', data => {
            if(data.orderby !== this.column) {
                this.selected = false;
                this.sort = 'asc';
            }
        });
    }
}
</script>
