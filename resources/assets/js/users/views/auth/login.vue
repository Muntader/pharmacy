
<template>
    <div>

        <span class="back" @click="$helper.back()">
        <i class="fa fa-arrow-circle-o-left fa-3x" aria-hidden="true"></i>
        </span>
        
        <!-- EXIT -->        

        <div class="background">
        <img src="/img/girl-background.jpg" width="100%" alt="background" />
        </div>
       <div class="login">

         <div v-if="username_found">     

            <div class="col-12 col-sm-8 col-lg-4 offset-sm-2 offset-lg-4 p-xs-5 mt-3 login-form">
                <div class="p-lg-4 pt-sm-1 p-4" @keyup.enter="LOGIN">
                 <div class="form-group">
                    <div class="col-12">
                        <div class="col-12 col-lg-6 col-sm-6 offset-lg-3 offset-sm-3">
                          <div class="image-circel">
                            <img :src="'/upload/users_image/'+ username_data.image" alt="username_data.name" width="100%">
                          </div>
                       </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-8 control-label">E-mail</label>
                    <div class="col-12">
                        <input class="form-control" name="email" v-validate="'required|email|max:50'"
                            :class="{'input': true, 'input-danger': errors.has('email') }" type="email" v-model="email"
                            placeholder="E-mail">
                        <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-8 control-label">Password</label>
                    <div class="col-12">
                        <input class="form-control" type="password" name="password" v-validate="'required|min:6|max:100'"
                            :class="{'input': true, 'input-danger': errors.has('password') }" v-model="password"
                            placeholder="Password">
                        <span v-show="errors.has('password')" class="help is-danger">{{errors.first('password')}}</span>
                    </div>
                </div>
                <div class="form-group" v-if="error">
                    <div class="col-12">
                        <div class="help is-danger">E-mail or password is incorrect</div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <button class="btn btn-outline-primary" @click="LOGIN" v-if="!button_loading">LOGIN</button>
                        <button class="btn btn-outline-danger"  @click="BACK" >BACK</button>
                        <button class="btn btn-outline-primary" v-if="button_loading"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div v-if="!username_found" class="col-12 col-sm-8 col-lg-4 offset-sm-2 offset-lg-4 p-xs-5 mt-3 login-form">
                <div class="p-lg-4 pt-sm-1 p-4">        
                <div class="form-group">
                    <label class="col-8 control-label">Pharmacy Username</label>
                    <div class="col-12">
                        <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">@</span>
                        <input class="form-control" name="email" v-validate="'required|alpha_dash|max:70'"
                            :class="{'input': true, 'input-danger': errors.has('email') }" type="email" v-model="username"
                            placeholder="Pharmacy Username">
                        </div>
                        <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
                    </div>
                </div>
                 <div class="form-group" v-if="message">
                    <div class="col-12">
                        <div class="help is-danger">{{message}}</div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <button class="btn btn-outline-primary" @click="CHECK_USERNAME" v-if="!button_loading">NEXT</button>
                        <button class="btn btn-outline-primary" v-if="button_loading"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                    </div>
                </div>
               <div class="form-group form-inline">
                    <div class="col-12 forget-password">
                        <router-link :to="{name: 'forget'}">Forget username or password    ?</router-link>
                    </div>
                </div>
                <div class="my-5 text-center">
                    Don't have an account?
                    <router-link :to="{name: 'plan'}">Signup</router-link>
                </div>
                </div>
            </div>


    

    </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                email: null,
                password: null,
                username: null,
                pharmacyChanel: false,
            };
        },
        computed: mapState({
            error: state => state.auth.error,
            username_data: state => state.auth.username_data,
            button_loading: state => state.auth.button_loading,
            username_found:  state => state.auth.username_found,
            message:  state => state.auth.message,
        }),
        methods: {
            LOGIN() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('LOGIN', {'email': this.email, 'password': this.password});
                    }
                })
            },

            CHECK_USERNAME(){
                  this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('CHECK_USERNAME', this.username);
                    }
                })
            },

            BACK(){
                this.$store.commit('CLEAR_USERNAME_DATA');
            }
        }
    }

</script>