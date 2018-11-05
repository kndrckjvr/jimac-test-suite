<template>
    <v-toolbar app :clipped-left=true color="primary">
        <v-toolbar-side-icon @click="toolbar" dark></v-toolbar-side-icon>

        <v-toolbar-title class="white--text"></v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
            <v-icon color="white">more_vert</v-icon>
        </v-btn>
    </v-toolbar>
</template>

<script>
export default {
    name: 'Toolbar',
    methods: {
        toolbar() {
            this.$store.dispatch('navigation/updateDrawer');
        }
    }
}
</script>
