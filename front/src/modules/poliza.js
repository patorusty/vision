import http from "../http-request";
import moment from "moment";

const API_URL = "/polizas";

const setMediodia = fecha => {
  var m = moment(fecha, "ddd MMM D YYYY HH:mm:ss ZZ");
  m.set("hour", 12);
  m.set("minute", 0);
  m.set("second", 0);
  return m.toJSON();
};

const state = () => ({
  polizas: [],
  poliza: {
    vigencia_desde: setMediodia(moment()),
    tipo_vigencia_id: 6,
    fecha_solicitud: setMediodia(moment()),
    endosos: []
  },
  loading: true,
  tipo_riesgos: [],
  forma_pagos: [],
  tipo_vigencias: [],
});
const mutations = {
  SET_POLIZAS(state, polizas) {
    state.loading = false;
    state.polizas = polizas;
  },
  SET_POLIZA(state, poliza) {
    state.poliza = poliza;
    state.loading = false;
  },
  RESET_POLIZA(state) {
    state.poliza = Object.assign(
      {},
      {
          vigencia_desde: setMediodia(moment()),
          tipo_vigencia_id: 6,
          fecha_solicitud: setMediodia(moment()),
          endosos: []
      }
    );
  },
  CREATE_POLIZA(state, poliza) {
    state.polizas.push(poliza);
  },
  UPDATE_POLIZA(state, poliza) {
    var item = state.polizas.find(item => item.id === poliza.id);
    return Object.assign(item, poliza);
  },
  DELETE_POLIZA(state, id) {
    state.polizas = state.polizas.filter(c => c.id != id);
  },
  SET_TIPO_RIESGOS(state, tipo_riesgos) {
    state.tipo_riesgos = tipo_riesgos;
  },
  SET_FORMA_PAGOS(state, forma_pagos) {
    state.forma_pagos = forma_pagos;
  },
  SET_TIPO_VIGENCIAS(state, tipo_vigencias) {
    state.tipo_vigencias = tipo_vigencias;
  },
  SET_NUMERO_SOLICITUD(state, numero_solicitud) {
    state.poliza.numero_solicitud = numero_solicitud;
  },
  // SET_ENDOSOS_POR_POLIZA(state, endosos) {
  //   state.poliza.endosos = endosos;  
  // },
};
const actions = {
  async getPolizas({ commit }) {
    const resp = await http.get(API_URL);
    commit("SET_POLIZAS", resp.data);
  },
  async getPoliza({ commit, dispatch, state }, id) {
    const resp = await http.getOne(API_URL, id);
    commit("SET_POLIZA", resp.data);
    dispatch('endoso/getEndososDePoliza', id, {root:true})
    dispatch('siniestro/getSiniestrosDePoliza', id, {root:true})
    dispatch('endoso/getTipoEndosos', null, {root:true})
    dispatch('endoso/getDetalleEndosos', null, {root:true})
    if (
      state.poliza.riesgo_automotor.length < 1 ||
      state.poliza.otro_riesgo.length < 1
    ) {
      commit(
        "modal/SHOW_MODAL",true, {root:true});
    }
  },
  async createPoliza({ commit }, poliza) {
    const resp = await http.post(API_URL, poliza);
    if (resp.status === 201) {
      commit("CREATE_POLIZA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza creada con éxito!"
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
  async updatePoliza({ commit }, poliza) {
    const resp = await http.put(API_URL, poliza.id, poliza);
    if (resp.status === 200) {
      commit("UPDATE_POLIZA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza editada con éxito!"
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
  async deletePoliza({ commit }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_POLIZA", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza eliminada con éxito!"
        },
        { root: true }
      );
      commit("modal/HIDE_MODAL", false, { root: true });
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
  async getTipoRiesgos({ commit }) {
    const resp = await http.get("tiporiesgos");
    commit("SET_TIPO_RIESGOS", resp.data);
  },
  async getFormaPagos({ commit }) {
    const resp = await http.get("formapagos");
    commit("SET_FORMA_PAGOS", resp.data);
  },
  async getTipoVigencias({ commit }) {
    const resp = await http.get("tipovigencias");
    commit("SET_TIPO_VIGENCIAS", resp.data);
  },
  async cargarUltimoNumeroSolicitud({ commit }) {
    const resp = await http.get("numerosolicitud");
    commit("SET_NUMERO_SOLICITUD", resp.data[0].numero_solicitud);
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
