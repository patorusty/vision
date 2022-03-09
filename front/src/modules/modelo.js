import http from "../http-request";

const API_URL = "/administracion/modelos";

const state = () => ({
  modelos: [],
  modelo: {},
  marca_id: null,
  loading: false
});
const mutations = {
  SET_MODELOS(state, modelos) {
    state.loading = false;
    state.modelos = modelos;
  },
  SET_MODELO(state, modelo) {
    state.modelo = modelo;
    state.modelo.nombreOriginal = modelo.nombre;
  },
  RESET_MODELO(state) {
    state.modelo = Object.assign({}, {});
  },
  UPDATE_MODELO(state, modelo) {
    const item = state.modelos.find(item => item.id === modelo.id);
    Object.assign(item, modelo);
  },
  CREATE_MODELO(state, modelo) {
    state.modelos.unshift(modelo);
  },
  DELETE_MODELO(state, id) {
    state.modelos = state.modelos.filter(u => u.id != id);
  },
  UPDATE_MARCA_ID(state, marca_id) {
    state.marca_id = marca_id;
  }
};
const actions = {
  async getModelos({ commit, state }) {
    if (state.modelos.length === 0) {
      const resp = await http.get(API_URL);
      commit("SET_MODELOS", resp.data);
    }
  },

  async getModelo({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_MODELO", resp.data);
  },

  async updateModelo({ commit }, modelo) {
    const resp = await http.put(API_URL, modelo.id, modelo);
    if (resp.status === 200) {
      commit("UPDATE_MODELO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Modelo editado con éxito!"
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

  async createModelo({ state, commit }, modelo) {
    modelo.automotor_marca_id = state.marca_id;
    const resp = await http.post(API_URL, modelo);
    if (resp.status === 201) {
      commit("CREATE_MODELO", resp.data);
      commit("SET_MODELO", resp.data);
      commit("version/RESET_MODELO_ID", resp.data.id, { root: true });
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Modelo creado con éxito!"
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

  async deleteModelo({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_MODELO", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Modelo eliminado con éxito!"
        },
        { root: true }
      );
    } else if (resp.status === 202) {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Existen versiones relacionados a este modelo"
        },
        { root: true }
      );
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
  },

  async getModelosPorMarca({ commit, state }) {
    if (state.marca_id) {
      const resp = await http.getOne("/modelos/filtrar", state.marca_id);
      commit("SET_MODELOS", resp.data);
    }
  },

  updateMarcaId({ commit, rootState }, marca_id) {
    commit("UPDATE_MARCA_ID", marca_id);
    const found = rootState.modelo.modelos.find(
      element => element.automotor_marca_id == rootState.modelo.marca_id
    );
    commit("version/RESET_MODELO_ID", found, { root: true });
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
