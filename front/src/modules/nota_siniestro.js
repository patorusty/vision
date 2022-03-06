import http from "../http-request";

const API_URL = "/siniestros/notas";

const state = () => ({
  notas_siniestro: [],
  nota_siniestro: {
    fecha: new Date()
  },
  loading: false
});

const mutations = {
  SET_NOTAS(state, nota) {
    state.notas_siniestro = nota;
    state.loading = false;
  },
  SET_NOTA(state, nota) {
    state.nota_siniestro = nota;
  },
  RESET_NOTA(state) {
    state.nota_siniestro = Object.assign({}, {});
  },
  UPDATE_NOTA(state, nota) {
    const item = state.notas_siniestro.find(item => item.id === nota.id);
    Object.assign(item, nota);
  },
  CREATE_NOTA(state, nota) {
    state.notas_siniestro.unshift(nota);
  },
  DELETE_NOTA(state, id) {
    state.notas_siniestro = state.notas_siniestro.filter(u => u.id != id);
  }
};

const actions = {
  async getNotas({ commit }) {
    const resp = await http.get(API_URL);
    commit("SET_NOTAS", resp.data);
  },

  async getNota({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_NOTA", resp.data);
  },

  async updateNota({ commit }, nota) {
    const resp = await http.put(API_URL, nota.id, nota);
    if (resp.status === 200) {
      commit("UPDATE_NOTA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Nota editada con éxito!"
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

  async createNota({ commit }, nota) {
    const resp = await http.post(API_URL, nota);
    if (resp.status === 201) {
      commit("CREATE_NOTA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Nota creada con éxito!"
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

  async deleteNota({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_NOTA", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Nota eliminada con éxito!"
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
