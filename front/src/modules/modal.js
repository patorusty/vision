const state = () => ({
    modal: false,
    edicion: false
})
const mutations = {
  SHOW_MODAL(state, val) {
    state.modal = true;
    state.edicion = val;
  },
  HIDE_MODAL(state, val) {
    state.modal = false;
    state.edicion = val;
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  getters: {}
}
