import http from "../http-request";

const API_URL = "/endosos";

const state = () => ({
  endosos: [],
  endoso: {},
  tipo_endosos: [],
  detalle_endosos: [],
  loading: true
});
const mutations = {
  SET_ENDOSOS(state, endosos) {
    state.endosos = endosos;
    state.loading = false;
  },
  SET_ENDOSO(state, endoso) {
    state.endoso = endoso;
    state.loading = false;
  },
  RESET_ENDOSO(state) {
    state.endoso = Object.assign({}, {});
  },
  RESET_ENDOSOS(state) {
    state.endosos = [];
  },
  CREATE_ENDOSO(state, endoso) {
    state.endosos.unshift(endoso);
  },
  UPDATE_ENDOSO(state, endoso) {
    var item = state.endosos.find(item => item.id === endoso.id);
    Object.assign(item, endoso);
  },
  DELETE_ENDOSO(state, id) {
    state.endosos = state.endosos.filter(c => c.id != id);
  },
  SET_TIPO_ENDOSOS(state, tipo_endosos) {
    state.tipo_endosos = tipo_endosos;
  },
  SET_DETALLE_ENDOSOS(state, detalle_endosos) {
    state.detalle_endosos = detalle_endosos;
  }
};
const actions = {
  async getEndosos({ commit, state }) {
    if (state.endosos.length === 0) {
      const resp = await http.get(API_URL);
      commit("SET_ENDOSOS", resp.data);
    }
  },
  async getEndoso({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_ENDOSO", resp.data);
  },
  async getEndososDePoliza({ commit }, id) {
    const resp = await http.getOne("/endosos/indexFiltrado", id);
    commit("SET_ENDOSOS", resp.data);
  },
  async createEndoso({ commit, dispatch }, endoso) {
    const resp = await http.post(API_URL, endoso);
    if (resp.status === 201) {
      commit("CREATE_ENDOSO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Endoso creado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
    await dispatch("poliza/checkPolizas", "", { root: true });
  },
  async updateEndoso({ commit, dispatch }, endoso) {
    const resp = await http.put(API_URL, endoso.id, endoso);
    if (resp.status === 200) {
      commit("UPDATE_ENDOSO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Endoso editado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
    await dispatch("poliza/checkPolizas", "", { root: true });
  },
  async deleteEndoso({ commit, dispatch }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_ENDOSO", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Endoso eliminado con éxito!"
        },
        { root: true }
      );
      commit("modal/HIDE_MODAL", false, { root: true });
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Algo salió mal..."
        },
        { root: true }
      );
    }
    await dispatch("poliza/checkPolizas", "", { root: true });
  },
  async getTipoEndosos({ commit }) {
    const resp = await http.get("tipoendosos");
    commit("SET_TIPO_ENDOSOS", resp.data);
  },
  async getDetalleEndosos({ commit }) {
    const resp = await http.get("detalleendosos");
    commit("SET_DETALLE_ENDOSOS", resp.data);
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
