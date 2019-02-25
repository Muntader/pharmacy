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
                            <router-link class="nav-link" :to="{name: 'profile'}">
                                <i class="fa fa-user" aria-hidden="true"></i> Profile</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'security'}">
                                <i class="fa fa-shield" aria-hidden="true"></i> Security</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'payment-update'}">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i> Payemnt update</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'payment-billing'}">Payemnt billing</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'payment-plan'}">Payment plan</router-link>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-8 p-2 p-sm-5 p-lg-5">

                    <div class="language">
                        <ul class="list-group">
                    
                      <li class="list-group-item">

                           <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="en" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>English, Us </b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">English</b>
                                    <img src="/img/flag/united-states.svg" alt="de" width="30">
                            </div>


                        </li>


                        <li class="list-group-item">

                           <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="de" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>Deutsch </b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">German</b>
                                    <img src="/img/flag/germany.svg" alt="de" width="30">
                            </div>


                        </li>
                        <li class="list-group-item">

                         <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="es" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>Español</b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">Spanish</b>
                                    <img src="/img/flag/spain.svg" alt="es" width="30">
                            </div>


                        </li>
                        <li class="list-group-item">

                        <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="fr" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>Français </b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">French</b>
                                    <img src="/img/flag/france.svg" alt="fr" width="30">
                            </div>


                        </li>
                        <li class="list-group-item">


                         <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="in" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>हिन्दी </b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">Hindi</b>
                                    <img src="/img/flag/india.svg" alt="in" width="30">
                            </div>


                        </li>
                        <li class="list-group-item">

                         <div class="radio pull-left">
                             <label class="custom-control custom-radio">
                                <input id="radioStacked2" name="radio-stacked" value="tr" v-model="language" type="radio" class="custom-control-input" required>
                                <span class="custom-control-indicator"></span>
                                 <span class="custom-control-description strong-text">
                                     <b>Türkçe </b>
                                </span>
                             </label>
                           </div>    

                            <div class="img pull-right">
                                    <b class="mr-1">Turkish</b>
                                    <img src="/img/flag/turkey.svg" alt="tr" width="30">
                            </div>

                        </li>
                        </ul>  
                    

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
                language: 'en',
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
            CHANGE_IMAGE() {
                const formData = new FormData();
                const image = document.getElementById("avatar-img-file");
                formData.append("image", image.files[0]);
                this.$store.dispatch('UPDATE_SETTING_USER_AVATAR', formData);
            },
            UPDATE_DETAILS() {
                this.$store.dispatch('UPDATE_SETTING_USER_DETAILS', {
                    name: this.profile_item.name,
                    username: this.profile_item.username,
                    email: this.profile_item.email,
                    password: this.user.password,
                    password_confirmation: this.user.password_confirmation
                });
            }
        }
    }
</script>