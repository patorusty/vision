import http from "../http-request";

const API_URL = "/configuracion/usuarios";

const state = () => ({
  usuarios: [],
  tipo_usuarios: [],
  usuario: {
    compania: "Vision",
    activo: true
  },
  validated_user: {}
});
const mutations = {
  SET_USUARIOS(state, usuarios) {
    state.usuarios = usuarios;
  },
  SET_TIPO_USUARIOS(state, tipo_usuarios) {
    state.tipo_usuarios = tipo_usuarios;
  },
  SET_USUARIO(state, usuario) {
    state.usuario = usuario;
  },
  SET_VALIDATED_USER(state, user) {
    state.validated_user = user;
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
    state.usuarios.unshift(usuario);
  },
  DELETE_USUARIO(state, id) {
    state.usuarios = state.usuarios.filter(u => u.id != id);
  }
};
const actions = {
  async getUsuarios({ commit }) {
    const resp = await http.get(API_URL);
    commit("SET_USUARIOS", resp.data);
  },
  async getTipoUsuarios({ commit }) {
    const resp = await http.get("/configuracion/tipousuario");
    commit("SET_TIPO_USUARIOS", resp.data);
  },
  async getUsuario({ commit }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_USUARIO", resp.data);
  },

  async updateUsuario({ commit }, usuario) {
    const resp = await http.put(API_URL, usuario.id, usuario);
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
    const resp = await http.post("http://vision.test/api/register", usuario);
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
    const resp = await http.delete(API_URL, id);
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
  },
  async getValidatedUser({ commit }) {
    const resp = await http.get("/user");
    commit("SET_VALIDATED_USER", resp.data);
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
