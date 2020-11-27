import http from "../http-request";

const API_URL = "/administracion/codigo_organizadores";

const state = () => ({
  codigo_organizadores: [],
  codigo_organizador: {}
});
const mutations = {
  SET_CODIGO_ORGANIZADORES(state, codigo_organizadores) {
    state.codigo_organizadores = codigo_organizadores;
  },
  SET_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    state.codigo_organizador = codigo_organizador;

  },
  RESET_CODIGO_ORGANIZADOR(state) {
    state.codigo_organizador = Object.assign({}, {});
  },
  UPDATE_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    const item = state.codigo_organizadores.find(
      item => item.id === codigo_organizador.id
    );
    Object.assign(item, codigo_organizador);
  },
  CREATE_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    state.codigo_organizadores.push(codigo_organizador);
  },
  DELETE_CODIGO_ORGANIZADOR(state, id) {
    state.codigo_organizadores = state.codigo_organizadores.filter(u => u.id != id);
  }
};
const actions = {
  async getCodigoOrganizador({ commit }) {
    const resp = await http.load(API_URL);
    commit("SET_CODIGO_ORGANIZADORES", resp.data);
  },

  async getCodigoOrganizador({ commit }, id) {
    const resp = await http.loadOne(API_URL, id);
    commit("SET_CODIGO_ORGANIZADOR", resp.data);
  },

  async updateCodigoOrganizador({ commit }, codigo_organizador) {
    const resp = await http.update(
      API_URL,
      codigo_organizador.id,
      codigo_organizador
    );
    if (resp.status === 200) {
      commit("UPDATE_CODIGO_ORGANIZADOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Organizador editado con éxito!"
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

  async createCodigoOrganizador({ commit }, codigo_organizador) {
    const resp = await http.create(API_URL, codigo_organizador);
    if (resp.status === 201) {
      commit("CREATE_CODIGO_ORGANIZADOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Organizador creado con éxito!"
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

  async deleteCodigoOrganizador({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_CODIGO_ORGANIZADOR", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Codigo Organizador eliminado con éxito!"
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
