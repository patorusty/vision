const state = () => ({
    modal: false,
    edicion: false,
    modal2: false,
    edicion2: false,
    modal3: false,
    edicion3: false
})
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
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  getters: {}
}
