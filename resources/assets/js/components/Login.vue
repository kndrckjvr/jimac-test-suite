<template>
    <v-container grid-list-md>
        <v-layout>
            <v-flex md3></v-flex>
            <v-flex md6>
                <v-card>
                    <v-progress-linear 
                        :indeterminate="true"
                        class="ma-0" 
                        :active="loading" />
                    <v-flex class="px-4 pb-4 pt-2">    
                        <v-card-title primary-title class="pb-0 mb-0">
                            <div class="full-width">
                                <h2 class="headline mb-0">Login to JIMAC Test Suite</h2>
                            </div>
                        </v-card-title>
                        <v-card-text>
                            <v-form>
                                <v-text-field
                                    label="Username or Email"
                                    v-model="username"
                                    :disabled="loading"
                                    @keydown.enter="sendUserDetails()"
                                    :error-messages="usernameError" />
                                <v-text-field
                                    label="Password"
                                    v-model="password"
                                    :disabled="loading"
                                    @keydown.enter="sendUserDetails()"
                                    :error-messages="passwordError"
                                    :append-icon="hidePass ? 'visibility' : 'visibility_off'"
                                    @click:append="() => (hidePass = !hidePass)"
                                    :type="hidePass ? 'password' : 'text'" />
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn 
                                color="primary"
                                :loading="loading"
                                @click="sendUserDetails()">
                                Login
                            </v-btn>
                        </v-card-actions>
                    </v-flex>
                </v-card>
            </v-flex>
            <v-flex md3></v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    name: 'Login',
    data: () => ({
        loading: false,
        hidePass: true,
        username: '',
        password: '',
        usernameError: [],
        passwordError: []
    }),
    methods: {
        sendUserDetails() {
            this.loading = true
            this.usernameError = []
            this.passwordError = []
            axios.post(this.baseUrl + 'api/user/login', {
                username:this.username,
                password:this.password
            }).then((res) => {
                this.loading = false
                if(res.data.status == 1) {
                    // set token
                    this.$cookies.set('token', res.data.user.token)
                    // set user
                    this.$store.commit('auth/setUser', { user : res.data.user })
                    this.$router.push('/dashboard')
                    return
                } else if(res.data.status == 2) {
                    this.$store.commit('snackbar/showError', { "text" : res.data.message })
                }
                for(var key in res.data.errors) {
                    switch(key) {
                        case "username":
                            this.usernameError = res.data.errors.username[0]
                            break
                        case "password":
                            this.passwordError = res.data.errors.password[0]
                            break
                    }
                }
            }).catch((e) => {
                this.loading = false
                this.$store.commit('snackbar/showError', { "text" : "Internal Server Error!" })
            })
        }
    },
    computed: mapGetters({
        baseUrl: 'extras/baseUrl'
    })
}
</script>

<style>

</style>
