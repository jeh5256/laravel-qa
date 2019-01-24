import policies from './policies';

export default {
    install(Vue, options) {
        Vue.prototype.authorize = (policy, model) => {
            if (!window.userAuth.signedIn) { return false; }
        
            if (typeof policy === 'string' && typeof model === 'object') {
                const user = window.userAuth.user;
               console.log(policies[policy](user, model));
                return policies[policy](user, model);
            }
        };

        Vue.prototype.signedIn = window.userAuth.signedIn;
    }
}
