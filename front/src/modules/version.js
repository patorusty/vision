import http from "../http-request";

const API_URL = "/administracion/versiones";

const state = () => ({
  versiones: [],
  version: {},
  loading: false,
  modelo_id: null,
  anio_id: null
});
const mutations = {
  SET_VERSIONES(state, versiones) {
    state.loading = false;
    state.versiones = versiones;
  },
  SET_VERSION(state, version) {
    state.version = version;
    state.version.nombreOriginal = version.nombre;
  },
  RESET_VERSION(state) {
    state.version = Object.assign({}, {});
  },
  UPDATE_VERSION(state, version) {
    const item = state.versiones.find(item => item.id === version.id);
    Object.assign(item, version);
  },
  CREATE_VERSION(state, version) {
    state.versiones.unshift(version);
  },
  DELETE_VERSION(state, id) {
    state.versiones = state.versiones.filter(u => u.id != id);
  },
  UPDATE_MODELO_ID(state, modelo_id) {
    state.modelo_id = modelo_id;
  },
  RESET_MODELO_ID(state, el) {
    state.modelo_id = el.id;
  },
  UPDATE_ANIO_ID(state, anio_id) {
    state.anio_id = anio_id;
  }
};
const actions = {
  async getVersiones({ commit, state }) {
    if (state.versiones.length === 0) {
      const resp = await http.get(API_URL);
      commit("SET_VERSIONES", resp.data);
    }
  },

  async getVersionesPorModelo({ commit }, modelo_id) {
    const resp = await http.getOne("versiones/filtrar", modelo_id);
    commit("SET_VERSIONES", resp.data);
  },

  async getVersionesPorModeloYanio({ commit, state }) {
    if (state.modelo_id && state.anio_id) {
      const obj = { modelo_id: state.modelo_id, anio_id: state.anio_id };
      const resp = await http.post("/anios/filtrar", obj);
      commit("SET_VERSIONES", resp.data.versiones);
    }
  },

  async getVersion({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_VERSION", resp.data);
  },

  async updateVersion({ commit }, version) {
    const resp = await http.put(API_URL, version.id, version);
    if (resp.status === 200) {
      commit("UPDATE_VERSION", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Version editada con éxito!"
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

  async createVersion({ state, commit }, version) {
    version.automotor_modelo_id = state.modelo_id;
    const resp = await http.post(API_URL, version);
    if (resp.status === 201) {
      commit("CREATE_VERSION", resp.data);
      commit("SET_VERSION", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Version creada con éxito!"
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

  async deleteVersion({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_VERSION", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Version eliminada con éxito!"
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
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
