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
                    <img :src="user_image">
                </v-list-tile-avatar>

                <v-list-tile-content>
                    <v-list-tile-title class="user-name">{{ user_name }}</v-list-tile-title>
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

    }),
    computed: mapGetters({
        drawer: 'navigation/shown',
        items: 'navigation/items',
        auth: 'auth/auth',
        user_name: 'auth/name',
        user_image: 'auth/image'
    }),
    methods: {
        logout() {
            // Test Cases
            this.$cookies.remove('testCaseId')
            this.$cookies.remove('testCaseTitle')

            // Modules
            this.$cookies.remove('moduleId')
            this.$cookies.remove('moduleName')

            // Test Scenarios
            this.$cookies.remove('testScenarioId')
            this.$cookies.remove('testScenarioName')

            this.$store.dispatch('auth/logout');
            this.$cookies.remove('token')
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
