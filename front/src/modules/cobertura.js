import http from "../http-request";

const API_URL = "/administracion/coberturas";

const state = () => ({
  coberturas: [],
  cobertura: {
    cobOriginal: '',
    activo: true
  },
  loading: false
});
const mutations = {
  SET_COBERTURAS(state, coberturas) {
    state.coberturas = coberturas;
  },
  SET_COBERTURA(state, cobertura) {
    state.cobertura = cobertura;
    state.cobertura.cobOriginal = cobertura.cobertura

  },
  RESET_COBERTURA(state) {
    state.cobertura = Object.assign({}, {
      cobOriginal: '',
      activo: true
    });
  },
  UPDATE_COBERTURA(state, cobertura) {
    const item = state.coberturas.find(
      item => item.id === cobertura.id
    );
    Object.assign(item, cobertura);
  },
  CREATE_COBERTURA(state, cobertura) {
    state.coberturas.push(cobertura);
  },
  DELETE_COBERTURA(state, id) {
    state.coberturas = state.coberturas.filter(u => u.id != id);
  }
};
const actions = {
  async getCoberturas({ commit }, compania_id) {
    const resp = await http.getOne('/coberturas/compania', compania_id);
    commit("SET_COBERTURAS", resp.data);
  },

  async getCobertura({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_COBERTURA", resp.data);
  },

  async updateCobertura({ commit }, cobertura) {
    const resp = await http.put(
      API_URL,
      cobertura.id,
      cobertura
    );
    if (resp.status === 200) {
      commit("UPDATE_COBERTURA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Cobertura editada con éxito!"
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

  async createCobertura({ commit }, cobertura) {
    const resp = await http.post(API_URL, cobertura);
    if (resp.status === 201) {
      commit("CREATE_COBERTURA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Cobertura creada con éxito!"
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

  async deleteCobertura({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_COBERTURA", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Cobertura eliminada con éxito!"
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
