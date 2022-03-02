// =========================================================
// * Vuetify Material Dashboard PRO - v2.1.0
// =========================================================
//
// * Product Page: https://www.creative-tim.com/product/vuetify-material-dashboard-pro
// * Copyright 2019 Creative Tim (https://www.creative-tim.com)
//
// * Coded by Creative Tim
//
// =========================================================
//
// * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "./plugins/base";
import "./plugins/chartist";
import "./plugins/vee-validate";
import "./plugins/vue-world-map";
import vuetify from "./plugins/vuetify";
import i18n from "./i18n";
// import VueMask from "v-mask";
// import VCalendar from "v-calendar";
import VueTheMask from "vue-the-mask";
Vue.use(VueTheMask);
// Vue.use(VueMask);
// Vue.use(VCalendar, {
//   componentPrefix: "vc"
// });

Vue.config.productionTip = false;

function isloggedIn() {
  return localStorage.getItem("isLoggedIn");
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.authOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!isloggedIn()) {
      next({
        path: "/login"
      });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.guestOnly)) {
    if (isloggedIn()) {
      next({
        path: "/"
      });
    } else {
      next();
    }
  } else {
    next(); // make sure to always call next()!
  }
});

Vue.directive("uppercase", {
  inserted: function(el, _, vnode) {
    el.addEventListener("input", async function(e) {
      e.target.value = e.target.value.toUpperCase();
      vnode.componentInstance.$emit("input", e.target.value.toUpperCase());
    });
  }
});

Vue.filter("upper", function(value) {
  if (!value) return "";
  value = value.toString();
  return value.toUpperCase();
});

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: h => h(App)
}).$mount("#app");
