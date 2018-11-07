<template>
     <v-navigation-drawer
        app
        fixed
        :clipped=true
        :value="drawer"
        >
        <v-list class="pa-1" v-if="$checkAuth([1,2], auth)">
            <v-list-tile avatar>
                <v-list-tile-avatar>
                    <!-- <img v-bind:src="user.image"> -->
                    <img src="https://randomuser.me/api/portraits/men/85.jpg">
                </v-list-tile-avatar>

                <v-list-tile-content>
                    <v-list-tile-title class="user-name">Juan Dela Cruz</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>

        <v-list class="pt-0" dense>
            <v-divider></v-divider>
            <v-list-tile
            v-for="item in items"
            v-if="$checkAuth(item.auth, auth)"
            :key="item.title"
            :to="item.to"
            @click="listItemClick(item.click)" >
            <v-list-tile-action>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>

                    <v-list-tile-content>
                    <v-list-tile-title class="nav-item-title">{{ item.title }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'Navigation',
    data: () => ({
        user: {
            image: "http://jimac-test-suite.test/public/images/default.png"
        }
    }),
    computed: mapGetters({
        drawer: 'navigation/shown',
        items: 'navigation/items',
        auth: 'auth/userAuth'
    }),
    methods: {
        logout() {
            this.$store.commit('auth/changeAuth', {auth: 0});
            this.$cookies.set('auth', 0)
            this.$cookies.remove('jts_token')
            this.$router.push('/login')
        },
        listItemClick(e) {
            if (typeof e === 'string') {
                this[e]()
            }
        }
    }
}
</script>

<style scoped>
.user-name {
    font-weight: bold;
}
</style>
