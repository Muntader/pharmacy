<template>
    <div>


            <div class="col-12 settings">

                <div class="col-12">
                    <div class="btn-group">
                        <router-link role="button" class="btn btn-md btn-outline-primary" :to="{name: 'profile'}">Profile</router-link>
                        <router-link role="button" class="btn btn-md btn-outline-primary" :to="{name: 'setting-language'}">Language</router-link>
                    </div>
                </div>

                <div class="col-md-8 col-lg-8 offset-md-2 p-2 p-sm-5 p-lg-5 offset-lg-2">

                    <div class="profile-details">

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" v-validate="'required|min:6'" :class="{'input': true, 'is-invalid': errors.has('password') }"
                                name="password" placeholder="Password" v-model="user.password">
                            <span v-show="errors.has('password')" class="text-danger">{{ errors.first('password') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="password">Verify password</label>
                            <input type="text" class="form-control" v-validate="'required|min:6'" :class="{'input': true, 'is-invalid': errors.has('password_confirmation') }"
                                name="password_confirmation" placeholder="Verify password" v-model="user.password_confirmation">
                            <span v-show="errors.has('password_confirmation')" class="text-danger">{{ errors.first('password_confirmation') }}</span>
                        </div>
                    
                        <div class="form-group">
                            <button class="btn btn-md btn-outline-primary" @click="UPDATE_PASSWORD">Update</button>
                        </div>
                    
                    </div>
                    </div>


                </div>
            </div>
        
</template>

<script>
    import {
        mapState
    } from 'vuex'
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'

    export default {
        name: 'profile-setting',

        data() {
            return {
                user: {
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        components: {
            ClipLoader
        },

        mounted() {
            this.$store.dispatch('GET_SETTING_USER_DETAILS');
        },

        methods: {
            UPDATE_PASSWORD(){
                this.$store.dispatch('UPDATE_SETTING_USER_PASSWORD',{password: this.user.password, password_confirmation: this.user.password_confirmation});
            }
        }
    }
</script>