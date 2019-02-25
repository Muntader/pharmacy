
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

        <transition name="slide-fade">
            <div class="col-12 col-sm-8 col-lg-4 offset-sm-2 offset-lg-4 p-xs-5 mt-3 p-3 login-form">
                <div class="p-lg-4 pt-sm-1 p-4" @keyup.enter="login">
                <div class="form-group">
                    <label class="col-8 control-label">E-mail</label>
                    <div class="col-12">
                        <input class="form-control" name="email" v-validate="'required|email|max:50'"
                            :class="{'input': true, 'input-danger': errors.has('email') }" type="email" v-model="email"
                            placeholder="E-mail">
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
                        <button class="btn btn-outline-primary" @click="SEND" v-if="!button_loading">SEND</button>
                        <button class="btn btn-outline-primary" v-if="button_loading"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                    </div>
                </div>
                </div>
            </div>
      </transition>

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
            message: state => state.auth.message,
            button_loading: state => state.auth.button_loading
        }),
        
        methods: {
            SEND() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('FORGET_SEND_MAIL', {email: this.email});
                    }
                })
            },
        }
    }

</script>