<template>
    <v-snackbar
        :timeout="0"
        :top="y === 'top'"
        :bottom="y === 'bottom'"
        :right="x === 'right'"
        :left="x === 'left'"
        :multi-line="mode === 'multi-line'"
        :vertical="mode === 'vertical'"
        v-model="snack_show" >
        <v-icon :color="snack_icon_color">{{ snack_icon }}</v-icon>&nbsp;&nbsp;&nbsp;{{ snack_text }}
      <v-btn
        color="primary"
        flat
        @click="closeSnack()" ><v-icon color="white">close</v-icon></v-btn>
    </v-snackbar>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    data: () => ({
        x: 'right',
        y: 'top',
        mode: ''
    }),
    methods: {
        closeSnack() {
            this.$store.dispatch('snackbar/hideSnack')
        }
    },
    computed: mapGetters({
        snack_show: 'snackbar/show',    
        snack_text: 'snackbar/text',
        snack_icon_color: 'snackbar/color',
        snack_icon: 'snackbar/icon'
    })
}
</script>