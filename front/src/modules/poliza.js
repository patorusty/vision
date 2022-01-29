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
  polizas_a_renovar:[],
  polizas_pendientes:[],
  poliza: {
    tipo_riesgo_id: 1,
    vigencia_desde: setMediodia(moment()),
    tipo_vigencia_id: 6,
    fecha_solicitud: setMediodia(moment()),
    endosos: []
  },
  loading: true,
  tipo_riesgos: [],
  riesgo:{},
  forma_pagos: [],
  tipo_vigencias: [],
  estados:[]
});
const mutations = {
  SET_POLIZAS(state, polizas) {
    state.loading = false;
    state.polizas = polizas;
  },
  SET_POLIZAS_PENDIENTES(state, polizas) {
    state.loading = false;
    state.polizas_pendientes = polizas;
  },
  SET_POLIZAS_A_RENOVAR(state, polizas) {
    state.loading = false;
    state.polizas_a_renovar = polizas;
  },
  SET_POLIZA(state, poliza) {
    state.poliza = poliza;
    state.loading = false;
  },
  RESET_POLIZA(state) {
    state.poliza = Object.assign(
      {},
      {
          tipo_riesgo_id: 1,
          vigencia_desde: setMediodia(moment()),
          tipo_vigencia_id: 6,
          fecha_solicitud: setMediodia(moment()),
          endosos: []
      }
    );
  },
  CREATE_POLIZA(state, poliza) {
    state.polizas.push(poliza);
    state.poliza = poliza;
  },
  UPDATE_POLIZA(state, poliza) {
    var item = state.polizas.find(item => item.id === poliza.id);
    return Object.assign(item, poliza);
  },
  UPDATE_POLIZA_PENDIENTE(state, poliza) {
    var item = state.polizas_pendientes.find(item => item.id === poliza.id);
    return Object.assign(item, poliza);
  },
  DELETE_POLIZA(state, id) {
    state.polizas = state.polizas.filter(c => c.id != id);
  },
  DELETE_POLIZA_PENDIENTE(state, id) {
    state.polizas_pendientes = state.polizas_pendientes.filter(c => c.id != id);
  },
  SET_TIPO_RIESGOS(state, tipo_riesgos) {
    state.tipo_riesgos = tipo_riesgos;
  },
  SET_FORMA_PAGOS(state, forma_pagos) {
    state.forma_pagos = forma_pagos;
  },
  SET_TIPO_VIGENCIAS(state, tipo_vigencias) {
    state.tipo_vigencias = tipo_vigencias;
  },SET_ESTADOS(state, estados) {
    state.estados = estados;
  },
  SET_NUMERO_SOLICITUD(state, numero_solicitud) {
    if (numero_solicitud == 0) {
      state.poliza.numero_solicitud = 1;
    } else {
      state.poliza.numero_solicitud = numero_solicitud + 1;
    }
  },
  // SET_ENDOSOS_POR_POLIZA(state, endosos) {
  //   state.poliza.endosos = endosos;  
  // },
  SET_RIESGO(state, riesgo) {
    state.riesgo = riesgo;
  },
};
const actions = {
  async getPolizas({ commit }) {
    const resp = await http.get(API_URL);
    commit("SET_POLIZAS", resp.data);
  },
  async getPolizasPendientes ({commit, dispatch}) {
    const resp = await http.get("/polizas/pendientes");
    commit("SET_POLIZAS_PENDIENTES", resp.data);
  },
  async getPolizasARenovar ({commit}) {
    const resp = await http.get("/polizas/a_renovar");
    commit("SET_POLIZAS_A_RENOVAR", resp.data);
  },
  async getPoliza({ commit, dispatch, state }, numero_solicitud) {
    const resp = await http.getOne(API_URL, numero_solicitud);
    dispatch('endoso/getEndososDePoliza', resp.data.id, {root:true})
    dispatch('siniestro/getSiniestrosDePoliza', resp.data.id, {root:true})
    commit("SET_POLIZA", resp.data);
    commit('riesgo/SET_RIESGO_AUTOMOTORES', state.poliza.riesgo_automotor, {root:true})
    dispatch('codigo_productor/getCodigoProductores', state.poliza.compania_id, {root:true})
    dispatch('endoso/getTipoEndosos', null, {root:true})
    dispatch('endoso/getDetalleEndosos', null, {root:true})
    dispatch('cobertura/getCoberturasActivas', resp.data.compania_id, {root:true})
    // if (
    //   state.poliza.riesgo_automotor.length < 1 ||
    //   state.poliza.otro_riesgo.length < 1
    // ) {
    //   commit(
    //     "modal/SHOW_MODAL",true, {root:true});
    // }
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
      console.log(resp.data);
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
  async updatePolizaPendiente({ commit }, poliza) {
    const resp = await http.put(API_URL, poliza.id, poliza);
    if (resp.status === 200) {
      console.log(resp.data);
      commit("UPDATE_POLIZA_PENDIENTE", resp.data);
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
  async getEstados({ commit }) {
    const resp = await http.get("estado_polizas");
    commit("SET_ESTADOS", resp.data);
  },
  async cargarUltimoNumeroSolicitud({ commit }) {
    const resp = await http.get("numerosolicitud");
    if(resp.data.length > 0){
      commit("SET_NUMERO_SOLICITUD", resp.data[0].numero_solicitud);
    } else {
      commit("SET_NUMERO_SOLICITUD", 0);
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
