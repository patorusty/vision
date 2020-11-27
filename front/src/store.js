import Vue from "vue";
import Vuex from "vuex";
import usuario from "./modules/usuario";
import modal from "./modules/modal";
import snackbar from "./modules/snackbar";
import compania from "./modules/compania";
import organizador from "./modules/organizador";
import productor from "./modules/productor";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    barColor: "rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)",
    barImage:
      "https://demos.creative-tim.com/material-dashboard-pro/assets/img/sidebar-1.jpg",
    drawer: null
  },
  mutations: {
    SET_BAR_IMAGE(state, payload) {
      state.barImage = payload;
    },
    SET_DRAWER(state, payload) {
      state.drawer = payload;
    },
    SET_SCRIM(state, payload) {
      state.barColor = payload;
    }
  },
  actions: {},
  modules: {
    usuario,
    modal,
    snackbar,
    compania,
    organizador,
    productor
  }
});
