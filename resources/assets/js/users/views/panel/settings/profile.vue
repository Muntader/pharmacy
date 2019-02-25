<template>
    <div>
        
        <div class="text-center spinner" v-if="loading">
            <clip-loader :loading="loading" :color="'#444'"></clip-loader>
        </div>


        <div v-if="profile_item !== null">

            <div class="col-12 settings">

                <div class="col-12">
                    <ul class="nav">
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'profile'}"><i class="fa fa-user" aria-hidden="true"></i> Profile</router-link>
                        </li>
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'security'}"><i class="fa fa-shield" aria-hidden="true"></i> Security</router-link>
                        </li>
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'payment-update'}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Payemnt update</router-link>
                        </li>
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'payment-billing'}">Payemnt billing</router-link>
                        </li>
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'language'}">Language</router-link>
                        </li>
                        <li class="nav-item">
                        <router-link  class="nav-link" :to="{name: 'invoice'}">Invoice</router-link>
                        </li>



                    </ul>
                </div>

                <div class="col-md-8 col-lg-8 offset-md-2 offset-lg-2  p-2 p-sm-5 p-lg-5">

                    <div class="profile-image text-center">
                        <div class="image-circle image-input">
                            <img :src="'/upload/users_image/'+$auth.getUserInfo('image')" :alt="$auth.getUserInfo('username')" width="200">
                           <label for="avatar-img-file"><i class="fa fa-pencil" aria-hidden="true">Change image</i></label>
                            <input type="file"
                                id="avatar-img-file"
                                name="avatar"
                                class="inputfile"
                                @change="CHANGE_IMAGE"
                                v-validate="'mimes:image/jpeg,image/png'">
                        </div>
                        <div class="text-center">
                            <span v-show="errors.has('avatar')" class="is-danger">{{ errors.first('avatar')}}</span>
                        </div>
                        </div>


                    <div class="profile-details">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" v-validate="'max:40|required'" :class="{'input': true, 'is-invalid': errors.has('name') }"
                                name="name" placeholder="Name" v-model="profile_item.name">
                            <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="username">Pharmacy username</label>
                            <input type="text" class="form-control" v-validate="'max:40|alpha_dash|required'" :class="{'input': true, 'is-invalid': errors.has('username') }"
                                name="username" placeholder="Pharmacy Username" v-model="profile_item.username">
                            <span v-show="errors.has('username')" class="text-danger">{{ errors.first('username') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" class="form-control" v-validate="'max:80|email|required'" :class="{'input': true, 'is-invalid': errors.has('email') }"
                                name="email" placeholder="Email" v-model="profile_item.email">
                            <span v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}</span>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-md btn-outline-primary" @click="UPDATE_DETAILS">Update</button>
                        </div>
                    
                    </div>
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
        computed: mapState({
            loading: state => state.settings.loading,
            profile_item: state => state.settings.profile_item,
        }),

        mounted() {
            this.$store.dispatch('GET_SETTING_USER_DETAILS');
        },

        methods: {
            CHANGE_IMAGE(){
                const formData = new FormData();
                const image = document.getElementById("avatar-img-file");
                formData.append("image", image.files[0]);
                this.$store.dispatch('UPDATE_SETTING_USER_AVATAR',formData);
            },
            UPDATE_DETAILS(){
                this.$store.dispatch('UPDATE_SETTING_USER_DETAILS',{name: this.profile_item.name, username: this.profile_item.username, email: this.profile_item.email, password: this.user.password, password_confirmation: this.user.password_confirmation});
            }
        }
    }
</script>