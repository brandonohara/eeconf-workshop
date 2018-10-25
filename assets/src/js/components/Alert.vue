<template>
    <div class="vue-component-alert">
        <b-alert :show="show" :variant="variant">{{ message }}</b-alert>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: false,
            variant: 'primary',
            message: ''
        }
    },

    methods: {
        /**
         * Show the alert box, set the message, and determine the color with the variant.
         * primary = blue, danger = red (error)
         */
        showAlert(message, variant = 'primary') {
            this.message = message;
            this.variant = variant;
            this.show = true;

            // hide the alert after 2 seconds
            setTimeout(() => {
                this.show = false;
            }, 2000);
        }
    },

    created() {
        //listen for the 'alert-message' event, and open the alert
        this.$root.$on('alert-message', message => {
            this.showAlert(message);
        });
        //listen for the 'alert-error' event, and open the alert with the danger variant
        this.$root.$on('alert-error', message => {
            this.showAlert(message, 'danger');
        });
    }
}
</script>
