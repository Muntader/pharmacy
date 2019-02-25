<style scoped>
    .credit-card-inputs.complete {
        border: 2px solid green;
    }

    .StripeElement {
        background-color: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
<template>
    <div>
        <span class="back" @click="$helper.back()">
            <i class="fa fa-arrow-circle-o-left fa-3x" aria-hidden="true"></i>
        </span>

        <!-- EXIT -->

        <div class="background">
            <img src="/img/girl-background.jpg" width="100%" alt="background" />
        </div>
        <div v-if="show && !username_found">
            <div class="col-12 payment">
                <div class="mt-5">
                    <div class="col-12 col-lg-8 offset-lg-2 p-3 payment-form">
                        <h5>STEP
                            <b style="color:#3498db;">3</b> OF 3</h5>
                        <h3>Set up your payment</h3>
                        <div class=" col-lg-10 offset-lg-1 ">
                            <card class='stripe-card pt-4' :class='{ complete }' :stripe='$helper.stripe_key()' :options='stripeOptions' @change='complete = $event.complete'
                            />
                            <small>No commitments, Cancel online at anytime</small>
                            <small class="is-danger">{{error}}</small>
                            <br>
                            <button v-if="!button_loading" class='btn btn-outline-primary mt-4 pay-with-stripe' @click='pay'>START MEMBERSHIP</button>
                            <button v-if="button_loading" class="btn btn-outline-primary" disabled>
                                <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="username_found">
            <div class="col-12 payment">
                <div class="col-4">
                    <a class="navbar-brand" href="#">THE
                        <b style="color:#3498db;">{{$t('app_name')}}</b>
                    </a>
                </div>
                <div class="p-lg-2 pt-sm-5 p-3">
                    <div class="col-12 col-lg-8 offset-lg-2 payment-form">
                        <h3>Welcome To {{$t('app_name')}}</h3>
                        <h5>Your {{$t('app_name')}} membership, which begins with a 1 week free trial.</h5>
                        <p>Cancel anytime before {{username_data.trail_end}} and will not be charged, to cancel go to your account
                            setting and Canel Membership.</p>
                        <br>
                        <h4>Your account details</h4>
                        <ul>
                            <li>Name: {{username_data.name}} </li>
                            <li>Email: {{username_data.email}}</li>
                            <li>Payment Information: ***********{{username_data.card_number}}</li>
                        </ul>
                        <router-link role="button" class="btn btn-primary" :to="{name: 'profile'}">Finish</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BounceLoader from "vue-spinner/src/BounceLoader.vue";
    import {
        Card,
        createToken,
        CardNumber,
        CardExpiry,
        CardCvc
    } from "vue-stripe-elements-plus";
    import {
        mapState
    } from 'vuex';
    export default {
        props: ["stripe", "options"],
        data() {
            return {
                error: null,
                complete: false,
                number: false,
                expiry: false,
                cvc: false,

                // info
                email: null,
                name: null,
                trail_end: null,
                show: false,
                plan: null,
                stripeOptions: {
                    // see https://stripe.com/docs/stripe.js#element-options for details
                },
            };
        },
        components: {
            Card,
            CardNumber,
            CardExpiry,
            CardCvc,
            BounceLoader
        },

        computed: mapState({
            username_data: state => state.auth.username_data,
            username_found: state => state.auth.username_found,
            button_loading: state => state.auth.button_loading,
        }),
        created() {
            if (this.$auth.isAuthenticated()) {
                axios.get('/api/user').then(info => {
                    if (info.data.status === 1000) {

                        this.show = true;

                    } else {

                        this.$router.push('/app');
                    }
                });
            } else {
                this.$router.push('/app');
            }
        },
        methods: {
            update() {
                this.complete = this.number && this.expiry && this.cvc;

                // field completed, find field to focus next
                if (this.number) {
                    if (!this.expiry) {
                        this.$refs.cardExpiry.focus();
                    } else if (!this.cvc) {
                        this.$refs.cardCvc.focus();
                    }
                } else if (this.expiry) {
                    if (!this.cvc) {
                        this.$refs.cardCvc.focus();
                    } else if (!this.number) {
                        this.$refs.cardNumber.focus();
                    }
                }
                // no focus magic for the CVC field as it gets complete with three
                // numbers, but can also have four
            },
            pay() {
                this.$store.commit('BUTTON_LOAD');
                // createToken returns a Promise which resolves in a result object with
                // either a token or an error key.
                // See https://stripe.com/docs/api#tokens for the token object.
                // See https://stripe.com/docs/api#errors for the error object.
                // More general https://stripe.com/docs/stripe.js#stripe-create-token.
                createToken().then(data => {
                    if (data.token.id !== null) {
                        // Get plan id
                        if (window.localStorage.getItem('_plan') == '1' || window.localStorage.getItem('_plan') ==
                            '2') {
                            this.plan_id = window.localStorage.getItem('_plan')
                        } else {
                            this.plan_id = 1;
                        }
                        this.$store.dispatch('PAYMENT', {
                            plan_id: this.plan_id,
                            token: data.token.id
                        })
                    }
                });
            },
            LOG_OUT() {
                this.$auth.destroyToken();
                this.$router.go('/')
            },
        }
    };
</script>