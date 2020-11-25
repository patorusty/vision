import http from "../http-request";

const state = () => ({
  usuarios: [],
  usuario: {
    compania: "Vision",
    activo: true
  }
});
const mutations = {
  SET_USUARIOS(state, usuarios) {
    state.usuarios = usuarios;
  },
  SET_USUARIO(state, usuario) {
    state.usuario = usuario;
  },
  RESET_USUARIO(state) {
    state.usuario = Object.assign(
      {},
      {
        compania: "Vision",
        activo: true
      }
    );
  },
  UPDATE_USUARIO(state, usuario) {
    const item = state.usuarios.find(item => item.id === usuario.id);
    Object.assign(item, usuario);
  },
  CREATE_USUARIO(state, usuario) {
    state.usuarios.push(usuario);
  },
  DELETE_USUARIO(state, id) {
    state.usuarios = state.usuarios.filter(u => u.id != id);
  }
};
const actions = {
  async getUsuarios({ commit }) {
    const resp = await http.load("configuracion/usuarios");
    commit("SET_USUARIOS", resp.data);
  },

  async getUsuario({ commit }, id) {
    const resp = await http.loadOne("configuracion/usuarios", id);
    commit("SET_USUARIO", resp.data);
  },

  async updateUsuario({ commit }, usuario) {
    const resp = await http.update(
      "configuracion/usuarios",
      usuario.id,
      usuario
    );
    if (resp.status === 200) {
      commit("UPDATE_USUARIO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Usuario editado con éxito!"
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
  },

  async createUsuario({ commit }, usuario) {
    const resp = await http.create("configuracion/usuarios", usuario);
    if (resp.status === 201) {
      commit("CREATE_USUARIO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Usuario creado con éxito!"
        },
        { root: true }
      );
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
  
  async deleteUsuario({ commit }, id) {
    const resp = await http.delete("configuracion/usuarios", id);
    if (resp.status === 200) {
      commit("DELETE_USUARIO", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Usuario eliminado con éxito!"
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
