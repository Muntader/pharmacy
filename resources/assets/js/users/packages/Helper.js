
import router from './Routes';

export default function (Vue) {

    Vue.helper = {
        back() {
            router.go(-1)
        },
        home() {
            router.push({ name: 'home' })
        },
        client_secret() {
            return 'XKMJbaNUARHrATaZB4HMKTHKEEqPpZZYhUQF4uHz';
        },
        stripe_key() {
            return 'pk_test_1MeRAI65kBVS1UVQOEgxXGsf';
        }
    }
    //make auth global 
    Object.defineProperties(Vue.prototype, {
        $helper: {
            get: () => {
                return Vue.helper
            }
        }
    })
}
