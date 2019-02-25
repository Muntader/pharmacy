<template>
  <div>
  <div class="col-12 signup" >

        <span class="back" @click="$helper.back()">
        <i class="fa fa-arrow-circle-o-left fa-3x" aria-hidden="true"></i>
        </span>
        
        <!-- EXIT -->        

        <div class="background">
        <img src="/img/girl-background.jpg" width="100%" alt="background" />
        </div>
          <div class="p-lg-2 pt-sm-5 p-3">
              <div class="col-12 col-lg-8 offset-lg-2 p-4 signup-form">
              <h5>STEP <b style="color:#3498db;">2</b> OF 3</h5>
              <h3>Just two more steps and you're done!</h3>
             <div class=" col-lg-10 offset-lg-1 ">
            <div class="form-group">
                <label class="col-8 control-label">Pharmacy name</label>
                <div class="col-12">
                    <input class="form-control" type="name" name="name" v-validate="'required|alpha_spaces|min:6|max:24'"
                           :class="{'input': true, 'input-danger': errors.has('name') }" v-model="name"
                           placeholder="Pharmacy name">
                    <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}</span>
                </div>
            </div>
             <div class="form-group">
                    <label class="col-8 control-label">Pharmacy Username</label>
                    <div class="col-12">
                        <div class="input-group">
                        <span class="input-group-addon">@</span>
                        <input class="form-control" name="username" v-validate="'required|alpha_dash|max:70'"
                            :class="{'input': true, 'input-danger': errors.has('username') }" type="text" v-model="username"
                            placeholder="Pharmacy Username">
                        </div>
                        <span v-show="errors.has('username')" class="help is-danger">{{errors.first('username')}}</span>
                    </div>
             </div>
            <div class="form-group">
                <label class="col-8 control-label">E-mail</label>
                <div class="col-12">
                    <input class="form-control" type="email" name="email" v-validate="'required|email|max:50'"
                           :class="{'input': true, 'input-danger': errors.has('email') }" v-model="email"
                           placeholder="E-mail">
                    <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
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
            <div class="form-group"  v-if="message" >
                <div class="col-12">
                    <span class="help is-danger">{{message}}</span>
                </div>
            </div>
            <div class="form-group">
               <div class="col-12">
                <p>By clicking on Sign up, you agree to <a style="color:#3498db;">terms & conditions</a> and <a style="color:#3498db;">privacy policy</a></p>
               </div>
            </div>
            <div class="form-group">
                <div class="col-12">
                    <button v-if="!button_loading" class="btn btn-outline-primary" @click="NEXT" >SIGN UP</button>
                    <button v-if="button_loading" class="btn btn-outline-primary" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
            </div>
            </div>
        </div>
    </div>
    </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "signup",
  data() {
    return {
      name: null,
      username: null,
      email: null,
      password: null,
      confirm: null
    };
  },
  computed: mapState({
    button_loading: state => state.auth.button_loading,
    message: state => state.auth.message
  }),

  methods: {
    NEXT() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("SIGNUP", {
            name: this.name,
            username: this.username,  
            email: this.email,
            password: this.password,
            confirm: this.confirm
          });
        }
      });
    }
  }
};
</script>

