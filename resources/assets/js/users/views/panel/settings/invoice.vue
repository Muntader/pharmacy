<template>
    <div>

        <!-- <div class="text-center spinner" v-if="loading">
            <clip-loader :loading="loading" :color="'#444'"></clip-loader>
        </div> -->


        <div v-if="invoice_item !== null">

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
                    
                    <div class="col-12">

                         <div class="form-group">
                            <label for="name">Pharmacy Name</label>
                            <input type="text" class="form-control" v-validate="'max:40'" :class="{'input': true, 'is-invalid': errors.has('name') }"
                                name="name" placeholder="Pharmacy name" v-model="invoice_item.name">
                            <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>                          
                         </div>

                         <div class="form-group">
                            <label for="address">Pharmacy Address</label>
                            <input type="text" class="form-control" v-validate="'max:40'" :class="{'input': true, 'is-invalid': errors.has('address') }"
                                name="address" placeholder="Pharmacy address" v-model="invoice_item.address">
                            <span v-show="errors.has('address')" class="text-danger">{{ errors.first('address') }}</span>                          
                         </div>

                         <div class="form-group">
                            <label for="telephone">Pharmacy Telephone</label>
                            <input type="text" class="form-control" v-validate="'max:15|numeric'" :class="{'input': true, 'is-invalid': errors.has('telephone') }"
                                name="telephone" placeholder="Pharmacy telephone" v-model="invoice_item.telephone">
                            <span v-show="errors.has('telephone')" class="text-danger">{{ errors.first('telephone') }}</span>                          
                         </div>

                         <div class="form-group">
                            <label for="email">Pharmacy E-mail</label>
                            <input type="text" class="form-control" v-validate="'max:70|email'" :class="{'input': true, 'is-invalid': errors.has('email') }"
                                name="email" placeholder="Pharmacy E-mail" v-model="invoice_item.email">
                            <span v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}</span>                          
                         </div>

                         <div class="form-group">
                            <label for="fax">Pharmacy Fax</label>
                            <input type="text" class="form-control" v-validate="'max:15|numeric'" :class="{'input': true, 'is-invalid': errors.has('fax') }"
                                name="fax" placeholder="Pharmacy fax" v-model="invoice_item.fax">
                            <span v-show="errors.has('fax')" class="text-danger">{{ errors.first('fax') }}</span>                          
                         </div>


                         <div class="form-group">
                            <label for="rate">Tax Rate </label>
                            <input type="text" class="form-control" v-validate="'max:70|numeric'" :class="{'input': true, 'is-invalid': errors.has('rate') }"
                                name="rate" placeholder="Rate Invoice" v-model="invoice_item.taxt_rate">
                            <span v-show="errors.has('rate')" class="text-danger">{{ errors.first('rate') }}</span>                          
                         </div>

                        <div class="form-group">
                            <label for="name">barcode type</label><br>
                                <select class="custom-select" v-model="invoice_item.barcode">
                                    <option value="EAN">EAN</option>
                                    <option value="UPC">UPC</option>
                                    <option value="EAN8">EAN8</option>
                                    <option value="EAN5">EAN5</option>
                                    <option value="EAN2">EAN2</option>
                                    <option value="CODE128">CODE128</option>
                                    <option value="CODE128">CODE128</option>
                                    <option value="pharmacode">Pharmacode</option>
                                    <option value="codabar">Codabar</option>
                                </select>
                         </div>


                         <div class="form-group">
                                <label for="name">Export invoice type</label><br>
                                <select class="custom-select" v-model="invoice_item.export_type">
                                    <option value="1">Normal printer</option>
                                    <option value="2">Fiscal printer </option>
                                </select>
                         </div>

                            <div class="form-group">
                                <label for="name">Currency type</label><br>
                                  <select class="custom-select" v-model="invoice_item.currency">
                                     <option value="USD">USD ($)</option>
                                     <option value="EUR">EUR (€)</option>
                                     <option value="INR">INR (Rs)</option>
                                     <option value="GBP">GBP (£)</option>
                                     <option value="BGN">BGN (лв)</option>
                                     <option value="TRY">TRY (TL)</option>
                                  </select>
                            </div>

                            <div class="form-group">
                                <button v-if="!update_loading" class="btn btn-outline-primary"  @click="UPDATE">{{$t('panel.settings.update')}}</button>
                                <button v-if="update_loading" class="btn btn-outline-primary" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>                            </div>
                           </div>
               
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState} from 'vuex'
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'

    export default {
        name: 'invoice',

        computed: mapState({
            loading: state => state.settings.loading,
            invoice_item: state => state.settings.invoice_item,
            button_loading: state => state.settings.button_loading,
        }),

        mounted() {
            this.$store.dispatch('GET_SETTING_INVOCIE_DETAILS');
        },

        methods: {
            UPDATE() {
                this.$store.dispatch('UPDATE_SETTING_INVOICE_DETAILS', this.invoice_item);
            }
        }
    }
</script>