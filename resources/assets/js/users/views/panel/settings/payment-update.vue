
<template>
    <div>

        <div class="text-center spinner" v-if="loading">
            <clip-loader :loading="loading" :color="'#444'"></clip-loader>
        </div>
      
       <div class="col-12 settings">

                <div class="col-12 col-sm-12 col-md-9 col-lg-9 p-2 p-sm-5 p-lg-5 profile-edit">
         
                      <div class="form-group">
                         <div class="col-12">   
                            <h4 v-if="info.card_brand === 'Visa'"><i class="fa fa-cc-visa" aria-hidden="true"></i> ************{{info.card_number}}</h4> 
                            <hr>
                         </div>
                         </div>      
                      <div class="form-group">
                         <div class="col-12">  
                        <label>{{$t('panel.settings.credit_card_or_debit')}}</label>
                     <template>
                        <card class='stripe-card'
                        :class='{ complete }'
                        :stripe='$helper.stripe_key()'
                        :options='stripeOptions'
                        @change='complete = $event.complete'
                        />
                      </template>
                        </div>
                      </div>
                    <small class="is-danger">{{error}}</small><br>
                        <div class="form-group">
                            <div class="col-12">
                                <button v-if="!update_loading" class="btn btn-outline-primary" :disabled='!complete' @click="UPDATE">{{$t('panel.settings.update')}}</button>
                                <button v-if="update_loading" class="btn btn-outline-primary" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                        <div class="col-12">
                        <b>{{$t('setting.cancel_memebrship')}}</b><br>
                        <small>{{$t('setting.cancel_note')}}</small><br>
                        <button v-show="!button_loading" v-if="payment_update.subscription_status === 'active' && !payment_update.cancel || payment_update.subscription_status === 'trialing' && !payment_update.cancel" class="btn btn-sm btn-outline-danger mt-2"  @click="CANCEL">{{$t('panel.settings.cancel_memebrship')}}</button>
                        <button v-show="!button_loading" v-if="payment_update.cancel  || payment_update.subscription_status === 'canceled' || payment_update.subscription_status === 'unpaid' || payment_update.subscription_status === 'past_due' && !button_loading" class="btn btn-sm btn-outline-danger mt-2" @click="RESUME">{{$t('panel.settings.resume_memebrship')}}</button>
                        <button class="btn  btn-sm btn-outline-danger m-2" v-if="button_loading" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                       </div>
                     </div> 
                    
                    </div>
                </div>
                </div>
</template>

<script>
import { mapState } from "vuex";
import { Card, createToken } from 'vue-stripe-elements-plus'
import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
export default {
  components: { Card,ClipLoader },
  data() {
    return {
      showModelError: false,
      success: false,
      complete: false,
      number: false,
      expiry: false,
      cvc: false,
      vise_brand: null,
      visa_number: null,
      error: null,
      resume: false,
      update_loading: false,
      stripeOptions: {
        style: {
          base: {
            iconColor: "#8898AA",
            color: "#767676",
            lineHeight: "36px",
            fontWeight: 300,
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSize: "19px",

            "::placeholder": {
              color: "#8898AA"
            }
          },
          invalid: {
            iconColor: "#e85746",
            color: "#e85746"
          }
        },
        classes: {
          focus: "is-focused",
          empty: "is-empty"
        }
      }
    };
  },
  computed: mapState({
    loading: state => state.settings.loading,
    info: state => state.settings.payment_info,
    payment_update: state => state.settings.payment_update,
    button_loading: state => state.settings.button_loading,
  }),
  mounted() {
    if (this.$auth.isAuthenticated()) {
      this.$store.dispatch("GET_SETTING_USER_PAYMENT_DETAILS");
    }
  },
  methods: {
    UPDATE() {
      // createToken returns a Promise which resolves in a result object with
      // either a token or an error key.
      // See https://stripe.com/docs/api#tokens for the token object.
      // See https://stripe.com/docs/api#errors for the error object.
      // More general https://stripe.com/docs/stripe.js#stripe-create-token.
      createToken().then(data => {
       if (data.token.id !== null) {
              this.$store.dispatch("SETTING_UPDATE_PAYMNET_CARD", data.token.id );
              this.$store.dispatch("GET_SETTING_USER_PAYMENT_DETAILS");
        }
      });
    },
    CANCEL() {
      this.$store.dispatch("SETTING_CANCEL_MEMBERSHIP");
    },
    RESUME() {
      this.$store.dispatch("SETTING_RESUME_MEMBERSHIP");
    },
  }
};
</script>