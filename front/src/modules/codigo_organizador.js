import http from "../http-request";

const API_URL = "/administracion/codigo_organizadores";

const state = () => ({
  codigo_organizadores: [],
  codigo_organizador: {
    codigo_organizador: "",
    coOriginal: '',
    activo: 1
  },
  loading: false
});
const mutations = {
  SET_CODIGO_ORGANIZADORES(state, codigo_organizadores) {
    state.codigo_organizadores = codigo_organizadores;
  },
  SET_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    state.codigo_organizador = codigo_organizador;
    state.codigo_organizador.coOriginal = codigo_organizador.codigo_organizador
  },
  RESET_CODIGO_ORGANIZADOR(state) {
    state.codigo_organizador = Object.assign({}, {
      codigo_organizador: "",
      coOriginal: "",
      activo: 1
    });
  },
  UPDATE_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    const item = state.codigo_organizadores.find(
      item => item.id === codigo_organizador.id
    );
    Object.assign(item, codigo_organizador);
  },
  CREATE_CODIGO_ORGANIZADOR(state, codigo_organizador) {
    state.codigo_organizadores.unshift(codigo_organizador);
  },
  DELETE_CODIGO_ORGANIZADOR(state, id) {
    state.codigo_organizadores = state.codigo_organizadores.filter(u => u.id != id);
  }
};
const actions = {
  async getCodigoOrganizadores({ commit }, compania_id) {
    const resp = await http.getOne('/codigo_organizador/compania', compania_id);
    commit("SET_CODIGO_ORGANIZADORES", resp.data);
  },

  async getCodigoOrganizador({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_CODIGO_ORGANIZADOR", resp.data);
  },

  async updateCodigoOrganizador({ commit }, codigo_organizador) {
    const resp = await http.put(
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
          snackText: "Código Organizador editado con éxito!"
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

  async createCodigoOrganizador({ commit }, codigo_organizador) {
    const resp = await http.post(API_URL, codigo_organizador);
    if (resp.status === 201) {
      commit("CREATE_CODIGO_ORGANIZADOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Código Organizador creado con éxito!"
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
          snackText: "Código Organizador eliminado con éxito!"
        },
        { root: true }
      );
    }
    else if (resp.status === 202) {
      commit(
        "snackbar/SHOW_SNACK", {
        color: "red",
        snackText: "Existen Códigos Productores relacionados a este Código Organizador"
      },
        { root: true }
      );
    } else {
      commit(
        "snackbar/SHOW_SNACK", {
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
