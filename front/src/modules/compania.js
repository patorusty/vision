import http from "../http-request";

const API_URL = "/administracion/companias";

const state = () => ({
  companias: [],
  comapnias_activas: [],
  compania: {
    cuit: "",
    activo: true,
    cuitOriginal: ""
  },
  loading: true
});
const mutations = {
  SET_COMPANIAS(state, companias) {
    state.companias = companias;
    state.loading = false;
  },
  SET_COMPANIAS_ACTIVAS(state, companias) {
    state.comapnias_activas = companias;
    state.loading = false;
  },
  SET_COMPANIA(state, compania) {
    state.compania = compania;
    state.compania.cuitOriginal = compania.cuit;
    state.loading = false;
  },
  RESET_COMPANIA(state) {
    state.compania = Object.assign(
      {},
      {
        cuit: "",
        activo: true,
        cuitOriginal: ""
      }
    );
  },
  CREATE_COMPANIA(state, compania) {
    state.companias.unshift(compania);
  },
  UPDATE_COMPANIA(state, compania) {
    var item = state.companias.find(item => item.id === compania.id);
    Object.assign(item, compania);
  },
  DELETE_COMPANIA(state, id) {
    state.companias = state.companias.filter(c => c.id != id);
  }
};
const actions = {
  async getCompanias({ commit, state }) {
    if (state.companias.length === 0) {
      const resp = await http.get(API_URL);
      commit("SET_COMPANIAS", resp.data);
    }
  },
  async getCompaniasActivas({ commit }) {
    const resp = await http.get("administracion/companias_activas");
    commit("SET_COMPANIAS", resp.data);
  },
  async getCompania({ commit, dispatch }, nombre) {
    const resp = await http.getOne(API_URL, nombre);
    commit("SET_COMPANIA", resp.data);
    dispatch("codigo_organizador/getCodigoOrganizadores", resp.data.id, {
      root: true
    });
    dispatch("codigo_productor/getCodigoProductores", resp.data.id, {
      root: true
    });
    dispatch("cobertura/getCoberturas", resp.data.id, { root: true });
  },
  async createCompania({ commit }, compania) {
    const resp = await http.post(API_URL, compania);
    if (resp.status === 201) {
      commit("CREATE_COMPANIA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Compañía creado con éxito!"
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
  },
  async updateCompania({ commit }, compania) {
    const resp = await http.put(API_URL, compania.id, compania);
    if (resp.status === 200) {
      commit("UPDATE_COMPANIA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Compañía editada con éxito!"
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
  },
  async deleteCompania({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_COMPANIA", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Compañía eliminada con éxito!"
        },
        { root: true }
      );
      commit("modal/HIDE_MODAL", false, { root: true });
    } else if (resp.status === 202) {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText:
            "Existen Códigos Organizadores relacionados a esta Compañía"
        },
        { root: true }
      );
    } else if (resp.status === 203) {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Existen Coberturas relacionadas a esta Compañía"
        },
        { root: true }
      );
    } else if (resp.status === 204) {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Existen Pólizas relacionadas a esta Compañía"
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
  async getPolizasVigentes({ commit }) {
    const resp = await http.get("/polizas/vigentes");
    commit("SET_COMPANIAS_ACTIVAS", resp.data);
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
