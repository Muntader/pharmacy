
<template>
    <div>
        <div v-if="show">


        <span class="back" @click="$helper.back()">
        <i class="fa fa-arrow-circle-o-left fa-3x" aria-hidden="true"></i>
        </span>
        
        <!-- EXIT -->        

        <div class="background">
        <img src="/img/girl-background.jpg" width="100%" alt="background" />
        </div>


       <div class="login">
            <div class="col-12 col-sm-8 col-lg-4 offset-sm-2 offset-lg-4 p-xs-5 mt-3 login-form">
                <div class="p-lg-4 pt-sm-1 p-4" @keyup.enter="login">
                 <div class="form-group">
                    <div class="col-12">
                        <div class="col-12 col-lg-6 col-sm-6 offset-lg-3 offset-sm-3">
                          <div class="image-circel">
                            <img :src="'/img/'+ data.image" :alt="data.name" width="100%">
                          </div>
                       </div>
                    </div>
                </div>
                  <div class="form-group">
                <label class="col-8 control-label">Password</label>
                <div class="col-12">
                    <input class="form-control" type="password" name="confirm-field" v-validate="'min:8|required'"
                           :class="{'input': true, 'input-danger': errors.has('password') }" v-model="password"
                           placeholder="Password">
                </div>
            </div>

            <div class="form-group">
                <label class="col-8 control-label">Re-enter password</label>
                <div class="col-12">
                    <input class="form-control" type="password" name="password"
                           v-validate="'min:8|required|confirmed:confirm-field'"
                           :class="{'input': true, 'input-danger': errors.has('password') }" v-model="confirm"
                           placeholder="Re-enter password" data-vv-as="password">
                    <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
                </div>
            </div>
                <div class="form-group">
                    <div class="col-12">
                        <button class="btn btn-outline-primary" @click="CHANGE" v-if="!button_loading">CHANGE</button>
                        <button class="btn btn-outline-primary" v-if="button_loading"><i class="fa fa-circle-o-notch fa-spin"></i> Change</button>
                    </div>
                </div>
                </div>
            </div>
            </div>
    </div>

    <div v-if="error">

        <b>Expired Link</b>

    </div>
    
    
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                password: null,
                confirm: null,
                show:false,
                error:false,
                data:{},
            };
        },
        computed: mapState({
            button_loading: state => state.auth.button_loading,
            message:  state => state.auth.message,
        }),

        mounted() {
          axios.post('/api/register/forget/check', { hash: this.$route.params.hash }).then(res => {
                if (res.data.status === 'success') {
                    this.data = res.data.data;
                    this.show = true;
                } else if (res.data.status === 'error') {
                    this.error = true;
                }
            },error => {
                if (error.response.status === 401) {
                    this.error = true;
                    setTimeout(() => {
                        this.$router.push({name: 'home'})
                    }, 1000);
                }else{
                    this.error = true;
                    setTimeout(() => {
                        this.$router.push({name: 'home'})
                    }, 1000);
                }
            })        
        },
        methods: {
            CHANGE() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('FORGET_PASSWORD_CHANGE', {'password': this.password,'confirm': this.confirm,'email': this.data.email, 'hash': this.$route.params.hash});
                    }
                })
            }
        }
    }

</script>