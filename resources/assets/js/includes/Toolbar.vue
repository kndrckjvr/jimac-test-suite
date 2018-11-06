<template>
    <v-toolbar app :clipped-left=true color="primary">
        <v-toolbar-side-icon @click="toolbar" dark></v-toolbar-side-icon>

        <v-toolbar-title class="white--text">{{ toolbar_title }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
            <v-icon color="white">more_vert</v-icon>
        </v-btn>
    </v-toolbar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'Toolbar',
    methods: {
        toolbar() {
            this.$store.dispatch('navigation/updateDrawer');
        }
    },
    computed: mapGetters({
        toolbar_title: 'extras/toolbar_title'
    })
}
</script>
