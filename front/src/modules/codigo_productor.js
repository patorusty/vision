import http from "../http-request";

const API_URL = "/administracion/codigo_productores";

const state = () => ({
  codigo_productores: [],
  codigo_productor: {
    codigo_productor: "",
    cpOriginal: '',
    activo: 1
  },
  loading: false
});
const mutations = {
  SET_CODIGO_PRODUCTORES(state, codigo_productores) {
    state.codigo_productores = codigo_productores;
  },
  SET_CODIGO_PRODUCTOR(state, codigo_productor) {
    state.codigo_productor = codigo_productor;
    state.codigo_productor.cpOriginal = codigo_productor.codigo_productor

  },
  RESET_CODIGO_PRODUCTOR(state) {
    state.codigo_productor = Object.assign({}, {
      codigo_productor: "",
      cpOriginal: '',
      activo: 1
    });
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
  async getCodigoProductores({ commit }, compania_id) {
    const resp = await http.getOne('/codigo_productor/compania', compania_id);
    commit("SET_CODIGO_PRODUCTORES", resp.data);
  },
  async getCodigoProductoresActivos({ commit }, compania_id) {
    const resp = await http.getOne('/codigo_productor_activo/compania', compania_id);
    commit("SET_CODIGO_PRODUCTORES", resp.data);
  },
  async getCodigoProductor({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_CODIGO_PRODUCTOR", resp.data);
  },

  async updateCodigoProductor({ commit }, codigo_productor) {
    const resp = await http.put(
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
          snackText: "Código Productor editado con éxito!"
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
    const resp = await http.post(API_URL, codigo_productor);
    if (resp.status === 201) {
      commit("CREATE_CODIGO_PRODUCTOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Código Productor creado con éxito!"
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
          snackText: "Código Productor eliminado con éxito!"
        },
        { root: true }
      );
    }else if (resp.status === 202) {
      commit(
          "snackbar/SHOW_SNACK", {
          color: "red",
          snackText: "Existen Pólizas relacionadas a este Código Productor"
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
