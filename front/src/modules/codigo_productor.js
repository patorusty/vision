import http from "../http-request";

const API_URL = "/administracion/codigo_productores";

const state = () => ({
  codigo_productores: [],
  codigo_productor: {}
});
const mutations = {
  SET_CODIGO_PRODUCTORES(state, codigo_productores) {
    state.codigo_productores = codigo_productores;
  },
  SET_CODIGO_PRODUCTOR(state, codigo_productor) {
    state.codigo_productor = codigo_productor;

  },
  RESET_CODIGO_PRODUCTOR(state) {
    state.codigo_productor = Object.assign({}, {});
  },
  UPDATE_CODIGO_PRODUCTOR(state, codigo_productor) {
    const item = state.codigo_productores.find(
      item => item.id === codigo_productor.id
    );
    Object.assign(item, codigo_productor);
  },
  CREATE_CODIGO_PRODUCTOR(state, codigo_productor) {
    state.codigo_productores.push(codigo_productor);
  },
  DELETE_CODIGO_PRODUCTOR(state, id) {
    state.codigo_productores = state.codigo_productores.filter(u => u.id != id);
  }
};
const actions = {
  async getCodigoProductores({ commit }) {
    const resp = await http.load(API_URL);
    commit("SET_CODIGO_PRODUCTORES", resp.data);
  },

  async getCodigoProductor({ commit }, id) {
    const resp = await http.loadOne(API_URL, id);
    commit("SET_CODIGO_PRODUCTOR", resp.data);
  },

  async updateCodigoProductor({ commit }, codigo_productor) {
    const resp = await http.update(
      API_URL,
      codigo_productor.id,
      codigo_productor
    );
    if (resp.status === 200) {
      commit("UPDATE_CODIGO_PRODUCTOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Productor editado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK"({
          color: "success",
          snackText: "Algo salió mal..."
        }),
        { root: true }
      );
    }
  },

  async createCodigoProductor({ commit }, codigo_productor) {
    const resp = await http.create(API_URL, codigo_productor);
    if (resp.status === 201) {
      commit("CREATE_CODIGO_PRODUCTOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Productor creado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },

  async deleteCodigoProductor({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_CODIGO_PRODUCTOR", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Productor eliminado con éxito!"
        },
        { root: true }
      );
    } else {
      commit(
        "snackbar/SHOW_SNACK"({
          color: "success",
          snackText: "Algo salió mal..."
        }),
        { root: true }
      );
    }
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
