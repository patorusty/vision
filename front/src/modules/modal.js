const state = () => ({
  modal: false,
  edicion: false,
  modal2: false,
  edicion2: false,
  modal3: false,
  edicion3: false,
  modal_ra: false,
  edicion_ra: false,
  edicion_or: false,
  modal_or: false,
  modal4: false,
  edicion4: false,
  step: 1
});
const mutations = {
  SHOW_MODAL(state, val) {
    state.modal = true;
    state.edicion = val;
  },
  HIDE_MODAL(state, val) {
    state.modal = false;
    state.edicion = val;
  },
  SHOW_MODAL2(state, val) {
    state.modal2 = true;
    state.edicion2 = val;
  },
  HIDE_MODAL2(state, val) {
    state.modal2 = false;
    state.edicion2 = val;
  },
  SHOW_MODAL3(state, val) {
    state.modal3 = true;
    state.edicion3 = val;
  },
  HIDE_MODAL3(state, val) {
    state.modal3 = false;
    state.edicion3 = val;
  },
  SHOW_MODAL4(state, val) {
    state.modal4 = true;
    state.edicion4 = val;
  },
  HIDE_MODAL4(state, val) {
    state.modal4 = false;
    state.edicion4 = val;
  },
  SHOW_MODAL_RA(state, val) {
    state.modal_ra = true;
    state.edicion_ra = val;
  },
  HIDE_MODAL_RA(state, val) {
    state.modal_ra = false;
    state.edicion_ra = val;
  },
  SHOW_MODAL_OR(state, val) {
    state.modal_or = true;
    state.edicion_or = val;
  },
  HIDE_MODAL_OR(state, val) {
    state.modal_or = false;
    state.edicion_or = val;
  },
  SET_STEP(state, val) {
    state.step = val;
  },
  SWITCH_EDICION(state, val) {
    state.edicion3 = val;
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  getters: {}
};
